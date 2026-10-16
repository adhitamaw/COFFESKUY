<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Menu $menu)
    {
        $cart = session('cart', []);

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['quantity']++;
        } else {
            $cart[$menu->id] = [
                'menu_name' => $menu->nama,
                'photo' => $menu->gambar,
                'price' => $menu->harga,
                'cafe' => optional($menu->cafe)->nama,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $menu->nama . ' masuk keranjang',
                'count' => collect($cart)->sum('quantity'),
            ]);
        }

        return back()->with('success', $menu->nama . ' masuk keranjang');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate(['quantity' => 'required|integer|min:0|max:99']);
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            if ($data['quantity'] === 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $data['quantity'];
            }
            session(['cart' => $cart]);
        }

        return back();
    }

    public function remove(int $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item dihapus.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        if (!config('stripe.sk')) {
            return redirect()->route('cart.index')->with('error', 'Pembayaran belum dikonfigurasi (STRIPE_SECRET kosong).');
        }

        // Harga diambil ulang dari database, bukan dari session
        $menus = Menu::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $lineItems = [];
        foreach ($cart as $id => $item) {
            if (!$menus->has($id)) {
                continue;
            }
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'idr',
                    'product_data' => ['name' => $menus[$id]->nama],
                    'unit_amount' => (int) $menus[$id]->harga * 100,
                ],
                'quantity' => (int) $item['quantity'],
            ];
        }

        try {
            \Stripe\Stripe::setApiKey(config('stripe.sk'));
            $session = \Stripe\Checkout\Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer_email' => $request->user()->email,
                'metadata' => ['user_id' => (string) $request->user()->id],
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cart.index'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout gagal: ' . $e->getMessage());

            return redirect()->route('cart.index')->with('error', 'Pembayaran gagal dibuat. Coba lagi nanti.');
        }

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        $paid = false;
        $sessionId = $request->query('session_id');

        if ($sessionId && config('stripe.sk')) {
            try {
                \Stripe\Stripe::setApiKey(config('stripe.sk'));
                $paid = \Stripe\Checkout\Session::retrieve($sessionId)->payment_status === 'paid';
            } catch (\Throwable $e) {
                Log::warning('Gagal cek status Stripe: ' . $e->getMessage());
            }
        }

        if ($paid) {
            session()->forget('cart');
        }

        return view('cart.success', compact('paid'));
    }
}

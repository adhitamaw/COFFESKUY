<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToOwner;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    use ScopesToOwner;

    public function index(Request $request)
    {
        $cafes = $this->cafesFor($request->user())->orderBy('nama')->get(['id', 'nama']);
        $cafeId = $request->query('cafe');
        $q = trim((string) $request->query('q', ''));

        $menus = Menu::with('cafe')
            ->whereIn('cafe_id', $cafes->pluck('id'))
            ->when($cafeId, fn ($query) => $query->where('cafe_id', $cafeId))
            ->when($q !== '', fn ($query) => $query->where('nama', 'like', "%{$q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.menus.index', compact('menus', 'cafes', 'cafeId', 'q'));
    }

    public function create(Request $request)
    {
        $cafes = $this->cafesFor($request->user())->orderBy('nama')->get(['id', 'nama']);

        if ($cafes->isEmpty()) {
            return redirect()->route('admin.cafes.create')->with('warning', 'Buat cafe dulu sebelum menambah menu.');
        }

        return view('admin.menus.form', ['menu' => new Menu(['cafe_id' => $request->query('cafe')]), 'cafes' => $cafes]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['gambar'] = $this->storeImage($request->file('gambar'));
        $data['user_id'] = $request->user()->id;

        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu ditambahkan.');
    }

    public function edit(Request $request, Menu $menu)
    {
        $this->authorizeMenu($request, $menu);
        $cafes = $this->cafesFor($request->user())->orderBy('nama')->get(['id', 'nama']);

        return view('admin.menus.form', compact('menu', 'cafes'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorizeMenu($request, $menu);

        $data = $this->validated($request, false);
        if ($request->hasFile('gambar')) {
            $this->deleteImage($menu->gambar);
            $data['gambar'] = $this->storeImage($request->file('gambar'));
        } else {
            unset($data['gambar']);
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu diperbarui.');
    }

    public function destroy(Request $request, Menu $menu)
    {
        $this->authorizeMenu($request, $menu);

        $this->deleteImage($menu->gambar);
        $menu->delete();

        return back()->with('success', 'Menu dihapus.');
    }

    private function authorizeMenu(Request $request, Menu $menu): void
    {
        abort_unless($menu->cafe && $request->user()->ownsCafe($menu->cafe), 403);
    }

    private function validated(Request $request, bool $creating): array
    {
        $cafeIds = $this->cafesFor($request->user())->pluck('id')->all();

        return $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer|min:0|max:100000000',
            'cafe_id' => ['required', Rule::in($cafeIds)],
            'gambar' => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');  // gabisa ke halaman register kecuali guest 
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,penjual'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);
    }

    /**
     * Pembeli langsung aktif, penjual menunggu persetujuan admin.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $isSeller = $data['role'] === 'penjual';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'status' => $isSeller ? 'pending' : 'approved',
        ]);

        if ($isSeller && isset($data['pdf'])) {
            $filename = time() . '_' . $user->id . '.pdf';
            $data['pdf']->move(public_path('pdfs'), $filename);
            $user->update(['pdf_file' => $filename]);
        }

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        if ($user->isSeller()) {
            return redirect('/')->with('info', 'Akun penjual terdaftar. Tunggu persetujuan admin untuk membuka dashboard.');
        }

        return redirect('/')->with('success', 'Selamat datang, ' . $user->name . '!');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (array_key_exists('role', $data) && $data['role'] === 'admin') {
            $rules['pdf'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }
    
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if (isset($data['role'])) {
            $status = ($data['role'] === 'user') ? 'rejected' : 'pending';
        } 
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => isset($data['role']) ? $data['role'] : null,
            'password' => Hash::make($data['password']),
            'status' => $status,
        ]);
        
       
        if (array_key_exists('pdf', $data) && $data['role'] === 'admin') {

            $uploadedPdf = $data['pdf'];

            $filename = time() . '.' . $uploadedPdf->getClientOriginalExtension();
            $uploadedPdf->move(public_path('pdfs'), $filename);
        
            $user->update(['pdf_file' => $filename]);
        
        }
    
        return $user;
        
    }
}

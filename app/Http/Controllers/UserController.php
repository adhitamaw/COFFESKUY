<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin dan developer yang bisa mengakses ini
        $this->middleware('isAdmin');
    }

    // Menampilkan semua user
    public function index()
    {
        $users = User::all();
        return view('admin.userIndex', compact('users'));
    }

    // Menerima / approve user
    public function accept(User $user)
    {
        $user->update(['status' => 'approved']);
        
        // Jika user adalah penjual, buat cafe otomatis untuknya
        if ($user->role == 'penjual') {
            // Buat cafe baru untuk penjual
            $cafe = new \App\Models\Cafe;
            $cafe->nama = 'Cafe ' . $user->name;
            $cafe->alamat = 'Alamat belum diisi';
            $cafe->content = 'Deskripsi cafe belum diisi';
            $cafe->gambar = 'default.jpg'; // Pastikan ada gambar default
            $cafe->user_id = $user->id;
            $cafe->latitude = '-6.914744';  // Default latitude (pusat Jakarta)
            $cafe->longitude = '107.6091336'; // Default longitude (pusat Jakarta)
            $cafe->save();
        }
        
        return redirect()->back()->with('success', 'User berhasil diapprove');
    }

    // Menolak user
    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);
        return redirect()->back()->with('warning', 'User ditolak');
    }

    // Menghapus user
    public function destroy(User $user)
    {
        // Hapus file PDF jika ada
        if ($user->pdf_file) {
            Storage::delete('pdfs/' . $user->pdf_file);
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
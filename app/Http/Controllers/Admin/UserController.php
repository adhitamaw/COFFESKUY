<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cafe;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $role = $request->query('role');
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($role, fn ($query) => $query->where('role', $role))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")))
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'status', 'role', 'q'));
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);

        // Penjual baru otomatis dibuatkan satu cafe kosong
        if ($user->isSeller() && !$user->cafes()->exists()) {
            Cafe::create([
                'nama' => 'Cafe ' . $user->name,
                'alamat' => 'Alamat belum diisi',
                'content' => 'Deskripsi belum diisi',
                'gambar' => 'default.jpg',
                'user_id' => $user->id,
            ]);
        }

        return back()->with('success', $user->name . ' disetujui.');
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);

        return back()->with('warning', $user->name . ' ditolak.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        foreach ($user->cafes as $cafe) {
            $cafe->deleteWithRelations();
        }
        Menu::where('user_id', $user->id)->delete();
        $user->reviews()->delete();
        $user->favorites()->delete();

        if ($user->pdf_file) {
            File::delete(public_path('pdfs/' . $user->pdf_file));
        }

        $user->delete();

        return back()->with('success', 'User dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToOwner;
use App\Http\Controllers\Controller;
use App\Models\Cafe;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    use ScopesToOwner;

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $cafes = $this->cafesFor($request->user())
            ->withRating()
            ->withCount('menus')
            ->with('user')
            ->when($q !== '', fn ($query) => $query->where('nama', 'like', "%{$q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.cafes.index', compact('cafes', 'q'));
    }

    public function create()
    {
        return view('admin.cafes.form', ['cafe' => new Cafe]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['gambar'] = $this->storeImage($request->file('gambar'));
        $data['user_id'] = $request->user()->id;

        Cafe::create($data);

        return redirect()->route('admin.cafes.index')->with('success', 'Cafe ditambahkan.');
    }

    public function show(Request $request, Cafe $cafe)
    {
        $this->authorizeCafe($request->user(), $cafe);

        $cafe->loadAvg('reviews', 'rating')->loadCount(['reviews', 'menus']);
        $reviews = $cafe->reviews()->with('user')->latest()->paginate(10);

        return view('admin.cafes.show', compact('cafe', 'reviews'));
    }

    public function edit(Request $request, Cafe $cafe)
    {
        $this->authorizeCafe($request->user(), $cafe);

        return view('admin.cafes.form', compact('cafe'));
    }

    public function update(Request $request, Cafe $cafe)
    {
        $this->authorizeCafe($request->user(), $cafe);

        $data = $this->validated($request, false);
        if ($request->hasFile('gambar')) {
            $this->deleteImage($cafe->gambar);
            $data['gambar'] = $this->storeImage($request->file('gambar'));
        } else {
            unset($data['gambar']);
        }

        $cafe->update($data);

        return redirect()->route('admin.cafes.index')->with('success', 'Cafe diperbarui.');
    }

    public function destroy(Request $request, Cafe $cafe)
    {
        $this->authorizeCafe($request->user(), $cafe);

        $image = $cafe->gambar;
        $cafe->deleteWithRelations();
        $this->deleteImage($image);

        return redirect()->route('admin.cafes.index')->with('success', 'Cafe dihapus.');
    }

    private function validated(Request $request, bool $creating): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'gambar' => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
    }
}

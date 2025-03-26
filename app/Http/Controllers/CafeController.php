<?php

namespace App\Http\Controllers;
use App\Models\cafe;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cafes = Cafe::with('reviews')->get();


        $cafes->each(function ($cafe) {
            $cafe->averageRating = $cafe->reviews->avg('rating');   
        });

        $sortedCafes = $cafes->sortByDesc('averageRating'); //sorting berdasarkan nilai rating

        if(request()->segment(1) == 'api') return response()->json([
            'error' => false,
            'list' => $sortedCafes,
        ]);

        return view('cafe.index', ['cafes' => $sortedCafes]);
    }

    public function indexAdmin()
    {
        $user = auth()->user();
        
        // Jika pengguna adalah admin, tampilkan semua cafe
        if ($user->role == 'admin') {
            $cafe = Cafe::all();
        } else {
            // Jika pengguna adalah penjual, tampilkan hanya cafe miliknya
            $cafe = $user->cafes ?? collect();
        }

        $title = 'Delete Cafe';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        
        return view('admin.cafeIndex', compact('cafe', 'title', 'text'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cafe.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'gambar' => 'required|mimes:jpg,jpeg,png',
            'content' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
    
        $filename = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('image'), $filename);
    
        
        $user_id = auth()->user()->id;
        
    
        $cafe = new Cafe;
        $cafe->nama = $request->nama;
        $cafe->alamat = $request->alamat;
        $cafe->gambar = $filename;
        $cafe->content = $request->content;
        $cafe->user_id = $user_id; 
        $cafe->latitude = $request->latitude; 
        $cafe->longitude = $request->longitude; 
    
        $cafe->save();
    
        return redirect('/table-cafe')->with('success','Data berhasil disimpan');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $reviews = Review::where('cafe_id', $id)->with('user')->orderBy('rating', 'desc')->get();
        $avgRatings = Review::where('cafe_id', $id)->avg('rating');

        $cafe = Cafe::find($id);

        // Cek apakah user sudah memberikan review
        $userHasReviewed = false;
        if (Auth::check()) {
            $userHasReviewed = Review::where('cafe_id', $id)
                                    ->where('user_id', Auth::id())
                                    ->exists();
        }

        return view('cafe.detail', compact('cafe', 'reviews', 'avgRatings', 'userHasReviewed'));
    }

    public function showAdmin($id)
    {
        $reviews = Review::where('cafe_id', $id)->with('user')->orderBy('rating', 'desc')->get();
        $cafe = Cafe::find($id);

        return view('admin.cafeDetail',compact('cafe', 'reviews'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function editAdmin($id)
    {
        $cafe = Cafe::find($id);
        return view('admin.cafeEdit', compact('cafe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'gambar' => 'required|mimes:jpg,jpeg,png',
            'content' => 'required'
        ]);
        $cafe = Cafe::find($id);

        $filename = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('image'), $filename);
        $cafe->gambar = $filename;
        

        $cafe->nama = $request->nama;
        $cafe->alamat = $request->alamat;
        $cafe->content = $request->content;
    
        $cafe->save();
    
        return redirect('/table-cafe')->with('success', 'Data cafe berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

         $cafe = Cafe::find($id);
         $cafe->menus()->delete();
         $cafe->reviews()->delete();
         $cafe->delete();
    
        return redirect('/table-cafe')->with('success','Data berhasil dihapus');
    }
    

    public function search(Request $request)
    {
        $query = $request->input('query');

        $keywords = explode(' ', $query);
        $cafes = Cafe::where(function ($queryBuilder) use ($keywords) {
            foreach ($keywords as $keyword) {
                $queryBuilder->where('nama', 'like', "%$keyword%");
            }
        })->get();

        return view('cafe.index', compact('cafes'));
    }

}

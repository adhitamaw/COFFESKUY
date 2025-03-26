<?php

namespace App\Http\Controllers;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class RatingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Anda harus login untuk memberikan rating.');
        }

        // Log request data for debugging
        Log::info('Rating request data:', $request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'rate' => 'required|integer|between:1,5',
            'review' => 'required|string',
            'cafe_id' => 'required|exists:cafe,id',
        ]);

        try {
            // Get authenticated user
            $user = Auth::user();
            
            // Periksa apakah user sudah pernah memberikan review untuk kafe ini
            $existingReview = Review::where('user_id', $user->id)
                                  ->where('cafe_id', $validatedData['cafe_id'])
                                  ->first();
            
            if ($existingReview) {
                return redirect()->back()->with('error', 'Anda sudah memberikan review untuk kafe ini. Satu user hanya boleh memberikan satu review per kafe.');
            }
            
            // Create a new review
            $review = new Review;
            $review->rating = $validatedData['rate'];
            $review->komentar = $validatedData['review'];
            $review->status = 0;
            $review->user_id = $user->id;
            $review->cafe_id = $validatedData['cafe_id'];

            // Log the review data before saving
            Log::info('Review data before save:', [
                'user_id' => $user->id,
                'cafe_id' => $validatedData['cafe_id'],
                'rating' => $validatedData['rate'],
                'review' => $validatedData['review']
            ]);

            // Save the review
            $review->save();

            // Redirect with success message
            return redirect()->back()->with('success', 'Review berhasil dikirim!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating review: ' . $e->getMessage());
            
            // Redirect with error message
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::find($id);
        
        if (!$review) {
            return redirect()->back()->with('error', 'Review tidak ditemukan.');
        }
        
        $cafeId = $review->cafe_id;
        $review->delete();

        // Cek role user
        if (auth()->user()->role === 'admin') {
            return redirect('/table-cafe/' . $cafeId)->with('success', 'Review berhasil dihapus.');
        } else {
            return redirect('/cafe/' . $cafeId)->with('success', 'Review berhasil dihapus.');
        }
    }
}

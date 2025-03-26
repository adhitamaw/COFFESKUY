<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Cafe;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    $user = \Auth::user(); 
    $favorites = $user->favorites()->with('cafe')->get();
 
        return view('favorite.index', compact('favorites'));
    }

    public function store(Request $request, $cafe) {
        $user = Auth::user();
        $favorite = $user->favorites()->where('cafe_id', $cafe)->first();
    
        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => false, 'message' => 'Favorite removed']);
        } else {
            $user->favorites()->create(['cafe_id' => $cafe]);
            return response()->json(['status' => true, 'message' => 'Favorite added']);
        }
    }
    
    
}

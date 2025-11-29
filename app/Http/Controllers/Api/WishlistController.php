<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = Wishlist::with('product')->where('user_id', $request->user()->id)->get();
        return response()->json($wishlist);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id
        ]);

        return response()->json($wishlist, 201);
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)->findOrFail($id);
        $wishlist->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:view,click,cart,purchase',
            'session_id' => 'nullable|string'
        ]);

        $interaction = Interaction::create([
            'user_id' => $request->user('sanctum')?->id,
            'product_id' => $request->product_id,
            'type' => $request->type,
            'session_id' => $request->session_id ?? session()->getId(),
        ]);

        return response()->json($interaction, 201);
    }
}

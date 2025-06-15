<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction;


class ReactionController extends Controller
{
    
public function react(Request $request)
{
    $request->validate([
        'post_id' => 'required|exists:posts,id',
        'type' => 'required|in:like,love,haha,sad,angry',
    ]);

    // $reaction = Reaction::firstOrCreate(
    //     [
    //         'user_id' => auth()->id(),
    //         'post_id' => $request->post_id,
    //         'type' => $request->type,
    //     ]
    // );

    $reaction = Reaction::where([
        'user_id' => auth()->id(),
        'post_id' => $request->post_id,
        'type' => $request->type,
    ])->first();

    if ($reaction) {
        $reaction->delete(); // Unlike / Remove reaction
    } else {
        Reaction::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'type' => $request->type,
        ]);
    }


    return redirect()->back();
}
}

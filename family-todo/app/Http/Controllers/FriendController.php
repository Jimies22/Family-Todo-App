<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // Send a friend request
    public function sendRequest($friendId)
    {
        $friend = User::findOrFail($friendId);

        // Check if already friends or request pending
        $existing = Friendship::where(function ($query) use ($friendId) {
            $query->where([
                ['user_id', Auth::id()],
                ['friend_id', $friendId],
            ])->orWhere([
                ['user_id', $friendId],
                ['friend_id', Auth::id()],
            ]);
        })->first();

        if ($existing) {
            return response()->json(['message' => 'Friend request already exists'], 409);
        }

        Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $friendId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Friend request sent']);
    }

    // Accept a friend request
    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);

        if ($friendship->friend_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $friendship->update(['status' => 'accepted']);

        return response()->json(['message' => 'Friend request accepted']);
    }

    // Decline a friend request
    public function declineRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);

        if ($friendship->friend_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $friendship->delete();

        return response()->json(['message' => 'Friend request declined']);
    }

    // Remove a friend
    public function removeFriend($friendId)
    {
        $friendship = Friendship::where(function ($query) use ($friendId) {
            $query->where([
                ['user_id', Auth::id()],
                ['friend_id', $friendId],
            ])->orWhere([
                ['user_id', $friendId],
                ['friend_id', Auth::id()],
            ]);
        })->where('status', 'accepted')->firstOrFail();

        $friendship->delete();

        return response()->json(['message' => 'Friend removed']);
    }

    // Get friends list
    public function friends()
    {
        $user = Auth::user();
        $friends = [];

        // Friends where current user sent accepted request
        $sentAccepted = Friendship::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with('friend')
            ->get()
            ->pluck('friend');

        // Friends where current user received accepted request
        $receivedAccepted = Friendship::where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->with('user')
            ->get()
            ->pluck('user');

        $friends = $sentAccepted->merge($receivedAccepted)->unique('id');

        return response()->json($friends);
    }

    // Get pending requests
    public function pendingRequests()
    {
        $requests = Friendship::where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return response()->json($requests);
    }
}

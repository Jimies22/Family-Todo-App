<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Friendships where this user sent the request
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    // Friendships where this user received the request
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    // Get all confirmed friends
    public function friends()
    {
        return User::where(function ($query) {
            // Friends where current user sent accepted request
            $query->whereIn('id', 
                Friendship::where('user_id', $this->id)
                    ->where('status', 'accepted')
                    ->pluck('friend_id')
            );
        })->orWhere(function ($query) {
            // Friends where current user received accepted request
            $query->whereIn('id',
                Friendship::where('friend_id', $this->id)
                    ->where('status', 'accepted')
                    ->pluck('user_id')
            );
        });
    }

    // Get friend suggestions (users who are not friends yet)
    public function getFriendSuggestions()
    {
        $friendIds = $this->friends()->pluck('id')->toArray();
        $pendingIds = $this->sentFriendRequests()
            ->where('status', 'pending')
            ->pluck('friend_id')
            ->toArray();
        $receivedIds = $this->receivedFriendRequests()
            ->where('status', 'pending')
            ->pluck('user_id')
            ->toArray();

        $excludeIds = array_merge([$this->id], $friendIds, $pendingIds, $receivedIds);

        return User::whereNotIn('id', $excludeIds)->get();
    }
}

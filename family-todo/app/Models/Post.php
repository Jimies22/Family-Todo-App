<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'content',
        'archived_at',
    ];

    public function comments()
{
    return $this->hasMany(Comment::class);
}
    public function reactions()
{
    return $this->hasMany(Reaction::class);
}


}

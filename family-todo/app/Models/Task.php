<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = ['title', 'due_date', 'is_done', 'description', 'user_id', 'archived_at'];

    protected $casts = [
        'due_date' => 'datetime',
        'archived_at' => 'datetime',
        'is_done' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for filtering
    public function scopeActive(Builder $query): void
    {
        $query->whereNull('archived_at');
    }

    public function scopeArchived(Builder $query): void
    {
        $query->whereNotNull('archived_at');
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('is_done', true);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('is_done', false);
    }

    // Helper methods
    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }

    public function isActive(): bool
    {
        return is_null($this->archived_at);
    }

    public function isCompleted(): bool
    {
        return $this->is_done;
    }

    public function isPending(): bool
    {
        return !$this->is_done;
    }

    public function archive(): void
    {
        $this->update(['archived_at' => now()]);
    }

    public function unarchive(): void
    {
        $this->update(['archived_at' => null]);
    }

    public function toggleArchive(): void
    {
        if ($this->isArchived()) {
            $this->unarchive();
        } else {
            $this->archive();
        }
    }

    // Accessor for completed status (for backward compatibility)
    public function getCompletedAttribute(): bool
    {
        return $this->is_done;
    }

    // Mutator for completed status (for backward compatibility)
    public function setCompletedAttribute($value): void
    {
        $this->is_done = $value;
    }
}

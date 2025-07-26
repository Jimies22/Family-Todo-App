<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'status',
        'is_pinned',
        'is_featured',
        'published_at',
        'expires_at',
        'metadata',
        'archived_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    // Scopes for filtering
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published');
    }

    public function scopeAnnouncements(Builder $query): void
    {
        $query->where('type', 'announcement');
    }

    public function scopeImportant(Builder $query): void
    {
        $query->where('type', 'important');
    }

    public function scopePinned(Builder $query): void
    {
        $query->where('is_pinned', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeNotExpired(Builder $query): void
    {
        $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeActive(Builder $query): void
    {
        $query->published()
              ->notExpired();
    }

    // Helper methods
    public function isAnnouncement(): bool
    {
        return $this->type === 'announcement';
    }

    public function isImportant(): bool
    {
        return $this->type === 'important';
    }

    public function isTaskSummary(): bool
    {
        return $this->type === 'task_summary';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'announcement' => 'Announcement',
            'task_summary' => 'Task Summary',
            'important' => 'Important Notice',
            'general' => 'General Post',
            default => 'Post'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            default => 'Unknown'
        };
    }

    public function getTypeColor(): string
    {
        return match($this->type) {
            'announcement' => 'bg-blue-100 text-blue-800',
            'task_summary' => 'bg-green-100 text-green-800',
            'important' => 'bg-red-100 text-red-800',
            'general' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'bg-yellow-100 text-yellow-800',
            'published' => 'bg-green-100 text-green-800',
            'archived' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Metadata helpers
    public function getMetadata(string $key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }

    public function setMetadata(string $key, $value): void
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->metadata = $metadata;
    }

    // Task-related methods for task summary posts
    public function getTaskList(): array
    {
        return $this->getMetadata('tasks', []);
    }

    public function setTaskList(array $tasks): void
    {
        $this->setMetadata('tasks', $tasks);
    }

    public function getCompletedTaskCount(): int
    {
        $tasks = $this->getTaskList();
        return collect($tasks)->where('completed', true)->count();
    }

    public function getTotalTaskCount(): int
    {
        return count($this->getTaskList());
    }

    public function getTaskProgressPercentage(): int
    {
        $total = $this->getTotalTaskCount();
        if ($total === 0) return 0;
        
        return round(($this->getCompletedTaskCount() / $total) * 100);
    }

    /**
     * Get the number of days left before archiving (for backward compatibility)
     */
    public function getDaysUntilArchived()
    {
        if ($this->expires_at) {
            $now = now();
            if ($now->gt($this->expires_at)) {
                return 0;
            }
            return round($now->diffInDays($this->expires_at, false));
        }

        // Fallback to old logic
        $archiveDate = $this->created_at->addWeek();
        $now = now();
        
        if ($now->gt($archiveDate)) {
            return 0;
        }
        
        return round($now->diffInDays($archiveDate, false));
    }

    /**
     * Get detailed time left (days, hours, minutes)
     */
    public function getDetailedTimeLeft()
    {
        $expiryDate = $this->expires_at ?? $this->created_at->addWeek();
        $now = now();
        
        if ($now->gt($expiryDate)) {
            return ['days' => 0, 'hours' => 0, 'minutes' => 0];
        }
        
        $diff = $now->diff($expiryDate);
        
        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i
        ];
    }

    /**
     * Get formatted time left string
     */
    public function getFormattedTimeLeft()
    {
        $timeLeft = $this->getDetailedTimeLeft();
        
        if ($timeLeft['days'] > 0) {
            return $timeLeft['days'] . ' ' . Str::plural('day', $timeLeft['days']) . ' left';
        } elseif ($timeLeft['hours'] > 0) {
            return $timeLeft['hours'] . ' ' . Str::plural('hour', $timeLeft['hours']) . ' left';
        } elseif ($timeLeft['minutes'] > 0) {
            return $timeLeft['minutes'] . ' ' . Str::plural('minute', $timeLeft['minutes']) . ' left';
        } else {
            return 'Expires soon';
        }
    }

    /**
     * Check if post is close to expiring (within 2 days)
     */
    public function isCloseToExpiry()
    {
        return $this->getDaysUntilArchived() <= 2;
    }

    /**
     * Check if post is very close to expiring (less than 1 day)
     */
    public function isVeryCloseToExpiry()
    {
        return $this->getDaysUntilArchived() < 1;
    }
}

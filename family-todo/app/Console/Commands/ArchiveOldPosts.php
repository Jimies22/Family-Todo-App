<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class ArchiveOldPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive posts that are older than 1 week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $postsToArchive = Post::where('created_at', '<', $oneWeekAgo)
            ->whereNull('archived_at')
            ->count();
            
        if ($postsToArchive > 0) {
            Post::where('created_at', '<', $oneWeekAgo)
                ->whereNull('archived_at')
                ->update(['archived_at' => Carbon::now()]);
                
            $this->info("Successfully archived {$postsToArchive} posts.");
        } else {
            $this->info('No posts to archive.');
        }
    }
}

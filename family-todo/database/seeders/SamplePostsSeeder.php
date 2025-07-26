<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;

class SamplePostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('is_admin', true)->first();
        $regularUser = User::where('is_admin', false)->first();

        if (!$adminUser) {
            $adminUser = User::first();
        }

        // Sample Announcements
        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'Welcome to Our Family Todo App!',
            'content' => 'Welcome everyone! This is our new family task management system. Here you can create tasks, track progress, and stay organized as a family. Let\'s make this work together!',
            'type' => 'announcement',
            'status' => 'published',
            'is_pinned' => true,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'Weekly Family Meeting - Sunday 2 PM',
            'content' => 'We will have our weekly family meeting this Sunday at 2 PM in the living room. Please come prepared with your task updates and any new items to discuss. Snacks will be provided!',
            'type' => 'announcement',
            'status' => 'published',
            'is_pinned' => false,
            'is_featured' => true,
            'published_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);

        // Sample Important Notices
        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'House Rules Update - Effective Immediately',
            'content' => 'New house rules have been implemented:\n\n1. All dishes must be washed immediately after use\n2. Lights must be turned off when leaving rooms\n3. Quiet hours: 10 PM - 7 AM\n4. Weekly room cleaning required\n\nPlease review and follow these rules.',
            'type' => 'important',
            'status' => 'published',
            'is_pinned' => true,
            'is_featured' => false,
            'published_at' => now(),
        ]);

        // Sample Task Summaries
        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'Weekly Chores Completed - Great Job!',
            'content' => 'This week\'s chores have been completed successfully:\n\n✅ Kitchen cleaned and organized\n✅ Laundry done and folded\n✅ Living room tidied up\n✅ Bathrooms cleaned\n\nEveryone did an excellent job! Keep up the good work.',
            'type' => 'task_summary',
            'status' => 'published',
            'is_pinned' => false,
            'is_featured' => false,
            'published_at' => now(),
            'metadata' => [
                'tasks' => [
                    ['title' => 'Kitchen cleaning', 'completed' => true],
                    ['title' => 'Laundry', 'completed' => true],
                    ['title' => 'Living room tidying', 'completed' => true],
                    ['title' => 'Bathroom cleaning', 'completed' => true],
                ]
            ]
        ]);

        // Sample General Posts
        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'Weekend Trip Planning',
            'content' => 'We\'re planning a family trip for next month. Please share your ideas for destinations and activities. We\'re thinking of either a beach trip or a mountain hiking adventure. What do you prefer?',
            'type' => 'general',
            'status' => 'published',
            'is_pinned' => false,
            'is_featured' => false,
            'published_at' => now(),
        ]);

        Post::create([
            'user_id' => $regularUser->id,
            'title' => 'New Recipe Ideas',
            'content' => 'I found some great new recipes we could try this week. Here are my suggestions:\n\n1. Homemade pizza with fresh ingredients\n2. Stir-fry with vegetables from our garden\n3. Slow cooker chicken curry\n\nLet me know which ones you\'d like to try!',
            'type' => 'general',
            'status' => 'published',
            'is_pinned' => false,
            'is_featured' => false,
            'published_at' => now(),
        ]);

        // Sample Draft Post
        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'Monthly Budget Review (Draft)',
            'content' => 'This is a draft for our monthly budget review. I\'m still working on the final numbers and will publish this once complete.',
            'type' => 'announcement',
            'status' => 'draft',
            'is_pinned' => false,
            'is_featured' => false,
            'published_at' => null,
        ]);

        // Sample Expired Post
        Post::create([
            'user_id' => $adminUser->id,
            'title' => 'Old Event Notice (Expired)',
            'content' => 'This is an old event notice that has expired. It should be automatically archived.',
            'type' => 'announcement',
            'status' => 'published',
            'is_pinned' => false,
            'is_featured' => false,
            'published_at' => now()->subDays(30),
            'expires_at' => now()->subDays(1),
        ]);

        // Sample Tasks (including archived ones)
        $users = User::all();
        
        // Active tasks
        Task::create([
            'user_id' => $adminUser->id,
            'title' => 'Plan weekly family meeting',
            'description' => 'Schedule and prepare agenda for Sunday family meeting',
            'due_date' => now()->addDays(3),
            'is_done' => false,
        ]);

        Task::create([
            'user_id' => $regularUser->id,
            'title' => 'Clean kitchen',
            'description' => 'Wash dishes, wipe counters, and organize pantry',
            'due_date' => now()->addDays(1),
            'is_done' => true,
        ]);

        Task::create([
            'user_id' => $adminUser->id,
            'title' => 'Review monthly budget',
            'description' => 'Go through expenses and plan for next month',
            'due_date' => now()->addDays(7),
            'is_done' => false,
        ]);

        // Archived tasks
        Task::create([
            'user_id' => $regularUser->id,
            'title' => 'Old completed task',
            'description' => 'This task was completed and archived',
            'due_date' => now()->subDays(10),
            'is_done' => true,
            'archived_at' => now()->subDays(5),
        ]);

        Task::create([
            'user_id' => $adminUser->id,
            'title' => 'Past due task',
            'description' => 'This task was past due and archived',
            'due_date' => now()->subDays(15),
            'is_done' => false,
            'archived_at' => now()->subDays(8),
        ]);

        Task::create([
            'user_id' => $regularUser->id,
            'title' => 'Completed project',
            'description' => 'Home renovation project that was completed',
            'due_date' => now()->subDays(20),
            'is_done' => true,
            'archived_at' => now()->subDays(12),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::create([
            'name' => 'Jane Cooper',
            'email' => 'demo@northwestern.edu',
            'role' => 'Administrator',
            'department' => 'Information Technology',
            'password' => 'password',
        ]);

        // A role long enough to wrap in the People table and on the person's page.
        User::create([
            'name' => 'Alexandra Montgomery-Fitzwilliam',
            'email' => 'alexandra.montgomery-fitzwilliam@northwestern.edu',
            'role' => 'Associate Director of Research Computing Infrastructure and Data Services',
            'department' => 'Northwestern Information Technology Research Computing and Data Services',
            'password' => 'password',
        ]);

        User::create([
            'name' => 'Marcus Lee',
            'email' => 'marcus.lee@northwestern.edu',
            'role' => 'Viewer',
            'department' => 'Feinberg School of Medicine',
            'password' => 'password',
        ]);

        // An unread notification, so the bell shows its count.
        Notification::make()
            ->title('Your report is ready')
            ->body('The quarterly access review finished.')
            ->sendToDatabase($demo);
    }
}

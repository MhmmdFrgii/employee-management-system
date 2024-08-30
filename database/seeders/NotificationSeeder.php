<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $notifications = [
        //     [
        //         'user_id' => 1,
        //         'title' => 'Info Notification',
        //         'message' => 'This is an informational notification.',
        //         'type' => 'info',
        //         'url' => 'https://example.com/info',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'user_id' => 1,
        //         'title' => 'Warning Notification',
        //         'message' => 'This is a warning notification.',
        //         'type' => 'warning',
        //         'url' => 'https://example.com/warning',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'user_id' => 1,
        //         'title' => 'Error Notification',
        //         'message' => 'This is an error notification.',
        //         'type' => 'error',
        //         'url' => 'https://example.com/error',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'user_id' => 1,
        //         'title' => 'Success Notification',
        //         'message' => 'This is a success notification.',
        //         'type' => 'success',
        //         'url' => 'https://example.com/success',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ];

        // // Insert data ke tabel notifications
        // DB::table('notifications')->insert($notifications);
    }
}

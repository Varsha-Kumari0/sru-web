<?php

namespace Database\Seeders;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return; // Skip if not enough users
        }

        foreach ($users as $user) {
            $otherUsers = $users->where('id', '!=', $user->id);
            
            if ($otherUsers->isEmpty()) continue;
            
            $connectionCount = min(rand(1, 3), $otherUsers->count());
            $selectedUsers = $otherUsers->random($connectionCount);

            foreach ($selectedUsers as $otherUser) {
                // Check if connection already exists (in either direction)
                $exists = Connection::where(function ($query) use ($user, $otherUser) {
                    $query->where('user_id', $user->id)
                        ->where('connected_user_id', $otherUser->id);
                })->orWhere(function ($query) use ($user, $otherUser) {
                    $query->where('user_id', $otherUser->id)
                        ->where('connected_user_id', $user->id);
                })->exists();

                if (!$exists) {
                    Connection::create([
                        'user_id' => $user->id,
                        'connected_user_id' => $otherUser->id,
                        'status' => rand(0, 1) ? 'connected' : 'pending',
                    ]);
                }
            }
        }
    }
}

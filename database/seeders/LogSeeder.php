<?php

namespace Database\Seeders;

use App\Models\Log;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some initial log entries
        Log::create([
            'user_id' => 1, // Super Admin
            'action' => 'System Initialization',
            'model_type' => 'System',
            'model_id' => 0,
            'created_at' => now(),
        ]);

        Log::create([
            'user_id' => 1, // Super Admin
            'action' => 'Created initial document types',
            'model_type' => 'DocumentType',
            'model_id' => 0,
            'created_at' => now(),
        ]);

        Log::create([
            'user_id' => 1, // Super Admin
            'action' => 'Created initial templates',
            'model_type' => 'Template',
            'model_id' => 0,
            'created_at' => now(),
        ]);

        Log::create([
            'user_id' => 1, // Super Admin
            'action' => 'Created initial number formats',
            'model_type' => 'NumberFormat',
            'model_id' => 0,
            'created_at' => now(),
        ]);
    }
}

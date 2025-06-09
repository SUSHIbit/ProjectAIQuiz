<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StorageSeeder extends Seeder
{
    public function run(): void
    {
        // Create temp directory for file uploads
        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }
        
        // Create .gitignore for temp directory
        Storage::put('temp/.gitignore', "*\n!.gitignore\n");
    }
}
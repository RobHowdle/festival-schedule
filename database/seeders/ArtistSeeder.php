<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to your CSV file
        $csvFile = database_path('seeders/csvs/artists.csv');

        // Check if the CSV file exists
        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        // Open the CSV file
        $file = fopen($csvFile, 'r');

        // Get the header row to map columns
        $header = fgetcsv($file);

        // Clear existing artists (optional - remove if you want to keep existing data)
        Artist::truncate();

        $artists = [];

        // Read each row from the CSV
        while (($row = fgetcsv($file)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Map CSV columns to database columns
            $artistData = array_combine($header, $row);

            // Prepare data for insertion (adjust these fields based on your CSV structure)
            $artists[] = [
                'name' => $artistData['name'] ?? '',
                'slug' => $artistData['slug'] ?? null,
                'description' => $artistData['description'] ?? null,
                'genre' => $artistData['genre'] ?? null,
                'image_url' => $artistData['image_url'] ?? null,
                'social_links' => isset($artistData['social_links']) ? json_encode(explode(',', $artistData['social_links'])) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Close the file
        fclose($file);

        // Insert artists in chunks for better performance
        collect($artists)->chunk(500)->each(function ($chunk) {
            Artist::insert($chunk->toArray());
        });

        $this->command->info('Successfully imported ' . count($artists) . ' artists from CSV.');
    }
}
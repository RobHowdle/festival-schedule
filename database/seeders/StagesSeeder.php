<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to your CSV file
        $csvFile = database_path('seeders/csvs/stages.csv');

        // Check if the CSV file exists
        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        // Open the CSV file
        $file = fopen($csvFile, 'r');

        // Get the header row to map columns
        $header = fgetcsv($file);

        // Clear existing stages (optional - remove if you want to keep existing data)
        Stage::truncate();

        $stages = [];

        // Read each row from the CSV
        while (($row = fgetcsv($file)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Map CSV columns to database columns
            $stageData = array_combine($header, $row);

            // Prepare data for insertion (adjust these fields based on your CSV structure)
            $stages[] = [
                'name' => $stageData['name'] ?? '',
                'location' => !empty($stageData['location']) ? $stageData['location'] : null,
                'w3w_link' => !empty($stageData['w3w_link']) ? $stageData['w3w_link'] : null,
                'capacity' => !empty($stageData['capacity']) ? (int)$stageData['capacity'] : null, // Convert to int or null
                'type' => !empty($stageData['type']) ? $stageData['type'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Close the file
        fclose($file);

        // Insert artists in chunks for better performance
        collect($stages)->chunk(500)->each(function ($chunk) {
            Stage::insert($chunk->toArray());
        });

        $this->command->info('Successfully imported ' . count($stages) . ' stages from CSV.');
    }
}
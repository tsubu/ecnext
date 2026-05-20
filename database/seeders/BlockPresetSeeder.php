<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlockPresetSeeder extends Seeder
{
    public function run(): void
    {
        $heroType = DB::table('block_types')->where('type_key', 'hero')->first();
        if ($heroType) {
            DB::table('block_presets')->insert([
                [
                    'block_type_id' => $heroType->id,
                    'name' => 'Premium Indigo Hero (Classic)',
                    'settings' => json_encode([
                        'title' => 'Revolutionary Experience',
                        'description' => 'Discover the next generation of digital commerce with our precision-engineered platform.',
                        'badge' => 'NEW ARRIVAL',
                        'button_text' => 'GET STARTED',
                        'button_url' => '#'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'block_type_id' => $heroType->id,
                    'name' => 'Minimal Dark Hero',
                    'settings' => json_encode([
                        'title' => 'Simplicity is Beauty',
                        'description' => 'Less is more. Experience the elegance of minimal design.',
                        'badge' => 'CURATED',
                        'button_text' => 'VIEW COLLECTION',
                        'button_url' => '#'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        $cmsType = DB::table('block_types')->where('type_key', 'static_cms')->first();
        if ($cmsType) {
            DB::table('block_presets')->insert([
                [
                    'block_type_id' => $cmsType->id,
                    'name' => 'Standard Rich Text',
                    'settings' => json_encode([
                        'content' => '<h2>Important Information</h2><p>This is a placeholder for your important store content.</p>'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

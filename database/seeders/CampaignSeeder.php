<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Support\Str;

class CampaignSeeder extends Seeder
{
    public function run()
    {
        $admin = User::first(); // Should be mantinoubello based on DatabaseSeeder

        $campaignImage = 'https://i.pinimg.com/736x/21/6b/6b/216b6b7a3a8b3b3b3b3b3b3b3b3b3b3b.jpg';

        $candidateImages = [
            'https://i.pinimg.com/736x/a0/bf/2b/a0bf2b8a050bd6238dbf71ab542ff86f.jpg',
            'https://i.pinimg.com/1200x/da/d4/eb/dad4ebad3262dd717cdf6066579ecbdb.jpg',
            'https://i.pinimg.com/736x/31/36/a1/3136a104179714acd3180a521e4437e0.jpg',
            'https://i.pinimg.com/736x/65/7c/8c/657c8c470897cc630fee49784152eb5b.jpg',
            'https://i.pinimg.com/736x/1f/72/56/1f7256ec83be9b63421d620388a626c8.jpg',
        ];

        $reineEsgis = Campaign::create([
            'user_id' => $admin->id,
            'name' => 'Élection Reine ESGIS 2026',
            'slug' => 'reine-esgis-' . Str::random(5),
            'code' => 'REINE2026',
            'description' => 'Élection de la Reine ESGIS : Un scrutin d\'exception célébrant le talent et l\'élégance de notre école.',
            'image_path' => $campaignImage,
            'video_path' => null,
            'status' => 'active',
            'is_private' => false,
        ]);

        foreach ($candidateImages as $index => $img) {
            Candidate::create([
                'campaign_id' => $reineEsgis->id,
                'user_id' => $admin->id,
                'name' => 'Candidate Reine ' . ($index + 1),
                'description' => 'Un profil d\'exception pour le scrutin Reine ESGIS 2026.',
                'image_path' => $img,
                'video_path' => null,
                'status' => 'accepted',
                'sort_order' => $index + 1
            ]);
        }
    }
}

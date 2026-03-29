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
        $admin = User::where('email', '=', 'admin@vote.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@vote.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        $userOne = User::where('email', '=', 'user@vote.com')->first();
        if (!$userOne) {
            $userOne = User::create([
                'name' => 'User One',
                'email' => 'user@vote.com',
                'password' => bcrypt('password'),
                'role' => 'user'
            ]);
        }

        $campaignImages = [
            'https://i.pinimg.com/736x/a0/bf/2b/a0bf2b8a050bd6238dbf71ab542ff86f.jpg',
            'https://i.pinimg.com/1200x/da/d4/eb/dad4ebad3262dd717cdf6066579ecbdb.jpg',
            'https://i.pinimg.com/736x/31/36/a1/3136a104179714acd3180a521e4437e0.jpg',
            'https://i.pinimg.com/736x/65/7c/8c/657c8c470897cc630fee49784152eb5b.jpg',
            'https://i.pinimg.com/736x/1f/72/56/1f7256ec83be9b63421d620388a626c8.jpg',
            'https://i.pinimg.com/736x/21/6b/6b/216b6b7a3a8b3b3b3b3b3b3b3b3b3b3b.jpg',
        ];

        $campaignVideos = [
            'https://www.w3schools.com/html/mov_bbb.mp4',
            'https://www.w3schools.com/html/movie.mp4',
            'https://cdn.coverr.co/videos/the-ocean-1632594689254.mp4',
            'https://cdn.coverr.co/videos/beach-waves-1632594695543.mp4',
        ];

        $candidateImages = [
            'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=800',
            'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=800',
            'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=800',
            'https://images.pexels.com/photos/189333/pexels-photo-189333.jpeg?auto=compress&cs=tinysrgb&w=800',
            'https://images.pexels.com/photos/279746/pexels-photo-279746.jpeg?auto=compress&cs=tinysrgb&w=800',
            'https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=800',
        ];

        $candidateVideos = [
            'https://www.w3schools.com/html/mov_bbb.mp4',
            'https://www.w3schools.com/html/movie.mp4',
        ];

        // 10 Random Campaigns
        for ($i = 1; $i <= 10; $i++) {
            $isWithImage = $i <= 6;
            $name = ($isWithImage ? 'Promotion ' : 'Excellence ') . Str::random(5);
            
            $campaign = Campaign::create([
                'user_id' => ($i === 1) ? $userOne->id : $admin->id,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(3),
                'code' => strtoupper(Str::random(8)),
                'description' => 'Un scrutin d\'exception au cœur du Tamarin Hôtel. Votez pour l\'excellence et contribuez au futur de notre établissement de luxe.',
                'image_path' => $isWithImage ? $campaignImages[$i-1] : null,
                'video_path' => !$isWithImage ? $campaignVideos[$i-7] : null,
                'status' => 'active',
                'is_private' => false,
            ]);

            // 6 to 15 Random Candidates
            $numCandidates = rand(6, 15);
            for ($j = 1; $j <= $numCandidates; $j++) {
                $hasVideo = rand(0, 1);
                Candidate::create([
                    'campaign_id' => $campaign->id,
                    'user_id' => $admin->id,
                    'name' => 'Candidat ' . Str::random(5),
                    'description' => 'Un profil exemplaire pour ce scrutin de prestige.',
                    'image_path' => !$hasVideo ? $candidateImages[array_rand($candidateImages)] : null,
                    'video_path' => $hasVideo ? $candidateVideos[array_rand($candidateVideos)] : null,
                    'status' => 'accepted'
                ]);
            }
        }

        // Reine ESGIS Campaign (Created last to be first in latest() queries)
        $reineEsgis = Campaign::create([
            'user_id' => $userOne->id,
            'name' => 'Reine ESGIS',
            'slug' => 'reine-esgis-' . Str::random(5),
            'code' => 'REINE2026',
            'description' => 'Élection de la Reine ESGIS : Un scrutin d\'exception célébrant le talent et l\'élégance.',
            'image_path' => 'https://instagram.fcoo1-2.fna.fbcdn.net/v/t51.82787-15/579902521_17849448276593261_2044387127009638706_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=110&ig_cache_key=Mzc2MjIxNTUxNjU1MjEzNzQ3OQ%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjE0NDB4MTc3NC5zZHIuQzMifQ%3D%3D&_nc_ohc=i8VPNx-d-hoQ7kNvwGPgHUL&_nc_oc=AdrnH8n2B7iCXuCDmdXlnIn2iOQqarKxZiGTsl6ag1iAK7ODhH90IQAvCtsVcTYw0dY&_nc_ad=z-m&_nc_cid=1398&_nc_zt=23&_nc_ht=instagram.fcoo1-2.fna&_nc_gid=fVnuS-mC1L2VxYER8nVSGA&_nc_ss=7a32e&oh=00_AfyZl6GxOefDD-xr3OOZDFwrsphiMkMC8F0y_2W2FmGEYw&oe=69CF06D9',
            'video_path' => null,
            'status' => 'active',
            'is_private' => false,
        ]);

        $reineCandidates = [
            'https://instagram.fcoo1-2.fna.fbcdn.net/v/t51.82787-15/587943033_17851609896593261_4744017064391218402_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=101&ig_cache_key=Mzc3MzgyOTk2NjEyNDA0NzI2Mw%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTA4MC5zZHIuQzMifQ%3D%3D&_nc_ohc=_NanvihHudMQ7kNvwEmTnxg&_nc_oc=AdqbjYv8XMLDH8p8_Nc7ymRNC62HxPkm3myrbgf1s1j2uxmyZ2dMYyRFYH1VkDpTZ0o&_nc_ad=z-m&_nc_cid=1398&_nc_zt=23&_nc_ht=instagram.fcoo1-2.fna&_nc_gid=fVnuS-mC1L2VxYER8nVSGA&_nc_ss=7a32e&oh=00_Afx4gKncNYorbnQNrTAKfwvlT-mCh4D8ZRGUBGgR8zKWyA&oe=69CF101A',
            'https://instagram.fcoo1-1.fna.fbcdn.net/v/t51.82787-15/587778858_17851609953593261_952990149208398976_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=104&ig_cache_key=Mzc3MzgzMDQ2MDY4Mjg0MTkxNw%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTA4MC5zZHIuQzMifQ%3D%3D&_nc_ohc=PeJWCTFsn34Q7kNvwGKRQOw&_nc_oc=Adrhj5czLGvpI3Cl58Lw8inn4_oKI9Zo_0Qi1XmABuO8ozfTfS9ee2XVZkbdhKw603Q&_nc_ad=z-m&_nc_cid=1398&_nc_zt=23&_nc_ht=instagram.fcoo1-1.fna&_nc_gid=fVnuS-mC1L2VxYER8nVSGA&_nc_ss=7a32e&oh=00_AfxO0HT7ODjRR5O4lC_Bg96JwkJtEjZ0i8IPihGYv0IPEw&oe=69CF1AC7',
            'https://instagram.fcoo1-2.fna.fbcdn.net/v/t51.82787-15/587707981_17851610004593261_6443158478673470676_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=106&ig_cache_key=Mzc3MzgzMDkyMTE1MDMwNTMwNA%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTA4MC5zZHIuQzMifQ%3D%3D&_nc_ohc=6pIHQGAgjhMQ7kNvwH-zj5C&_nc_oc=AdpTQZ4DDqEzk6Zmjw4Vmsq5me5VGCYz8WDXk3FSiF93-fQkg9vTKpWotRl2SnemVK8&_nc_ad=z-m&_nc_cid=1398&_nc_zt=23&_nc_ht=instagram.fcoo1-2.fna&_nc_gid=87nkSTxP-uDpyrk5Q7uftw&_nc_ss=7a32e&oh=00_AfxaRTGdNUc9xd9bJYaLC7ax_YL7sAiW-2BtS6Ymyy1LBg&oe=69CF2660',
            'https://instagram.fcoo1-1.fna.fbcdn.net/v/t51.82787-15/588016009_17851610553593261_6582776477008408532_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=102&ig_cache_key=Mzc3MzgzMzgwODc2OTI4NzEzOA%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTA4MC5zZHIuQzMifQ%3D%3D&_nc_ohc=a1QXtR1hK10Q7kNvwFgjEdT&_nc_oc=Adpiiz--cVd2koL_oeV2q-BJL0A8itgORLRxkqq3_VkhDixVPqkY5ZUHQ1EGCEtM5mQ&_nc_ad=z-m&_nc_cid=1398&_nc_zt=23&_nc_ht=instagram.fcoo1-1.fna&_nc_gid=87nkSTxP-uDpyrk5Q7uftw&_nc_ss=7a32e&oh=00_AfwXTN5V9aL5xztY4JL4887cLHHq5uoMWB2WvHVbj60rRA&oe=69CF0F34',
            'https://instagram.fcoo1-2.fna.fbcdn.net/v/t51.82787-15/588963695_17851610766593261_3221461775956914956_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=105&ig_cache_key=Mzc3MzgzNTE3NDQxNzg4NDM4MQ%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTA4MC5zZHIuQzMifQ%3D%3D&_nc_ohc=T7_0OBc3wcsQ7kNvwH4ojWL&_nc_oc=Adp6MLvXMpgvxAB7MDIcXxJEzyr_KQYZWIfaJTNji2_a-OhifxamHmsLSwh-Zd-ChHM&_nc_ad=z-m&_nc_cid=1398&_nc_zt=23&_nc_ht=instagram.fcoo1-2.fna&_nc_gid=87nkSTxP-uDpyrk5Q7uftw&_nc_ss=7a32e&oh=00_AfwPSFFwuMjbNiGAKjyexuwTiijkjJrfzgjDGKT4xgXZYQ&oe=69CF11DE',
        ];

        foreach ($reineCandidates as $index => $img) {
            Candidate::create([
                'campaign_id' => $reineEsgis->id,
                'user_id' => $admin->id,
                'name' => 'Candidate Reine ' . ($index + 1),
                'description' => 'Un profil d\'exception pour le scrutin Reine ESGIS 2026.',
                'image_path' => $img,
                'video_path' => null,
                'status' => $index < 2 ? 'pending' : 'accepted'
            ]);
        }
    }
}

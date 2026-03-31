<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignVisit extends Model
{
    protected $fillable = ['campaign_id', 'candidate_id', 'ip_address', 'session_id', 'user_id', 'hits'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}

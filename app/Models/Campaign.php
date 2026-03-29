<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'code', 'description', 
        'image_path', 'video_path', 'status', 'is_private', 
        'password', 'start_at', 'end_at', 'rejection_reason'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_private' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($campaign) {
            $campaign->slug = Str::slug($campaign->name) . '-' . Str::random(5);
            $campaign->code = strtoupper(Str::random(8));
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class)->where(function($query) {
            $query->where('status', 'accepted');
        });
    }

    public function allCandidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               (is_null($this->start_at) || $this->start_at->isPast()) && 
               (is_null($this->end_at) || $this->end_at->isFuture());
    }
}

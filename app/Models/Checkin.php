<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Checkin extends Model
{
    protected $fillable = [
        'user_id',
        'text',
        'tags',
        'voice_path',
    ];

    protected $casts = [
        'tags' => 'array', // Auto convert JSON <-> array
    ];

    protected $appends = ['voice_url']; // Include in API responses

    public function getVoiceUrlAttribute()
    {
        return $this->voice_path
            ? config('app.url') . '/storage/app/public/' . $this->voice_path
            : null;
    }
    /**
     * Mutator: Handle comma-separated or array tags.
     */
    public function setTagsAttribute($value)
    {
        if (is_string($value)) {
            // Convert "tag1, tag2, tag3" → ["tag1", "tag2", "tag3"]
            $value = array_filter(array_map('trim', explode(',', $value)));
        }

        // Make sure it's always JSON encoded before saving
        $this->attributes['tags'] = json_encode(array_values((array) $value));
    }
}

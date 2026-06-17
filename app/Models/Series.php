<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;

class Series extends Model
{
    use HasFactory;

    protected $table = 'series';

    protected $fillable = [
        'user_id',
        'streaming_service_id',
        'name',
        'synopsis',
        'year',
        'imdb_id',
        'cover_path',
        'cover_url',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function streamingService(): BelongsTo
    {
        return $this->belongsTo(StreamingService::class);
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function episodes(): HasManyThrough
    {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    public function coverDisplayUrl(): ?string
    {
        if ($this->cover_path) {
            return Storage::disk('public')->url($this->cover_path);
        }

        return $this->cover_url;
    }

    public function progressPercent(): int
    {
        $total = $this->episodes()->count();

        if ($total === 0) {
            return 0;
        }

        $watched = $this->episodes()->where('watched', true)->count();

        return (int) round(($watched / $total) * 100);
    }
}

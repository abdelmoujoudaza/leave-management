<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Station extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'direction_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'latitude'  => 'double',
        'longitude' => 'double',
    ];

    /**
     * Get the direction that owns the station
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }

    /**
     * Get all of the passengers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'approved_by',
        'leave_type_id',
        'number',
        'description',
        'start_date',
        'end_date',
        'status',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'number'     => 'float',
    ];

    /**
     * Get the user associated with the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get the type associated with the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(LeaveType::class);
    }

    /**
     * Get the user associated with the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function approvedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
}

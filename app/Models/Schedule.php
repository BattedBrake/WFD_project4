<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'quota',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function hasAvailableQuota(): bool
    {
        return $this->reservations()->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_HOLD])->count() < $this->quota;
    }
}

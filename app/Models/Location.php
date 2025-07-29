<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
        'user_id',
    ];
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

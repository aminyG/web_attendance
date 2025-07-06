<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'dob', 'address', 'phone', 'email', 'employee_number', 'photo'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    // public function getPhotoUrlAttribute()
    // {
    //     return $this->photo ? asset('storage/' . $this->photo) : null;
    // }
}
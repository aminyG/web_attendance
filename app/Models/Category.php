<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
   protected $fillable = ['name', 'required_attendance_per_day', 'user_id']; 
   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'category_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class,'category_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
           
            if (!$category->id) {
                $category->id = (string) Str::uuid();
            }
        });
    }
}

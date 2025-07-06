<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'required_attendance_per_day'];


    public function employees()
    {
        return $this->hasMany(Employee::class, 'category_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

}

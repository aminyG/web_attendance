<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    protected $fillable = ['employee_id', 'date', 'status'];

    // Tambahkan metode untuk mendapatkan status dalam format yang lebih mudah dibaca
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'Hadir' => 'Hadir',
            'Izin' => 'Izin',
            'Sakit' => 'Sakit',
            'Alpha' => 'Alpha',
            default => 'Tidak Diketahui',
        };
    }

    public function category()
    {
        return $this->employee?->category();
    }
    public function schedule()
    {
        return $this->belongsTo(\App\Models\Schedule::class);
    }

}

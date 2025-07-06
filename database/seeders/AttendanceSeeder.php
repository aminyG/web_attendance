<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $schedules = Schedule::all();

        foreach ($employees as $employee) {
            // Dummy 5 hari ke belakang
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subDays($i)->toDateString();

                foreach ($schedules->where('category_id', $employee->category_id) as $schedule) {
                    Attendance::create([
                        'employee_id' => $employee->id,
                        'schedule_id' => $schedule->id,
                        'date' => $date,
                        'time' => Carbon::parse($schedule->start_time)->addMinutes(rand(0, 30))->format('H:i:s'),
                        'status' => rand(0, 1) ? 'Hadir' : 'Terlambat',
                    ]);
                }
            }
        }
    }
}

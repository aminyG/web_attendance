<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Schedule as WorkSchedule;

class MarkAlphaAttendance extends Command
{
    protected $signature = 'attendance:mark-alpha';
    protected $description = 'Mark Alpha for employees who miss attendance deadline';

    public function handle()
    {
        $today = now()->toDateString();
        $nowTime = now()->format('H:i:s');

        $schedules = WorkSchedule::with('category')->get();

        foreach ($schedules as $schedule) {
            if ($nowTime < $schedule->start_time) {
                continue;
            }

            $categoryId = $schedule->category_id;
            $employees = Employee::where('category_id', $categoryId)->get();

            foreach ($employees as $employee) {
                $already = Attendance::where('employee_id', $employee->id)
                    ->where('schedule_id', $schedule->id)
                    ->where('date', $today)
                    ->exists();

                if (!$already) {
                    Attendance::create([
                        'employee_id' => $employee->id,
                        'schedule_id' => $schedule->id,
                        'date' => $today,
                        'time' => null,
                        'status' => 'Alpha',
                    ]);
                }
            }
        }

        $this->info('Alpha marked for missing attendances!');
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->everyMinute();
    }
}
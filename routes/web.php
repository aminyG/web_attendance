<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return redirect()->route('login'); // Arahkan langsung ke halaman login
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
// Route::prefix('admin')->middleware(['auth', 'role:admin|super-admin'])->group(function () {
//     Route::get('/karyawan', [EmployeeController::class, 'index'])->name('employee.index');
//     Route::get('/karyawan/create', [EmployeeController::class, 'create'])->name('employee.create');
//     Route::post('/karyawan/store', [EmployeeController::class, 'storeIndividual'])->name('employee.store');
//     Route::get('/karyawan/create-mass', [EmployeeController::class, 'createMass'])->name('employee.createMass');
//     Route::post('/karyawan/store-mass', [EmployeeController::class, 'storeMass'])->name('employee.storeMass');
// });

Route::get('/karyawan', [EmployeeController::class, 'index'])->name('employee.index');
Route::get('/karyawan/create', [EmployeeController::class, 'create'])->name('employee.create');
Route::post('/karyawan/store', [EmployeeController::class, 'storeIndividual'])->name('employee.store');
Route::get('/karyawan/create-mass', [EmployeeController::class, 'createMass'])->name('employee.createMass');
Route::post('/karyawan/store-mass', [EmployeeController::class, 'storeMass'])->name('employee.storeMass');
Route::post('/employee/individual', [EmployeeController::class, 'storeIndividual'])->name('employee.store.individual');
Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');
Route::post('/karyawan/store-mass', [EmployeeController::class, 'storeMass'])->name('employee.storeMass');

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');

Route::get('/categories/attendance-settings', [CategoryController::class, 'showAttendanceSettings'])->name('categories.attendanceSettings');
Route::post('/categories/update-attendance', [CategoryController::class, 'updateAttendance'])->name('categories.updateAttendance');

Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedule.index');
Route::post('/schedules/store', [ScheduleController::class, 'store'])->name('schedule.store');
Route::post('/schedule/storeAll', [ScheduleController::class, 'storeAll'])->name('schedule.storeAll');
Route::delete('/schedule/delete/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

Route::resource('locations', LocationController::class);
Route::post('/locations/{location}/set-active', [LocationController::class, 'setActive'])->name('locations.setActive');
Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');

// Memuat file autentikasi
require __DIR__ . '/auth.php';

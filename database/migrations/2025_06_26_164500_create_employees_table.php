<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->date('dob'); // Tanggal Lahir
            $table->string('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('employee_number')->unique(); // No Pegawai
            $table->string('photo')->nullable(); // Foto Profil
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

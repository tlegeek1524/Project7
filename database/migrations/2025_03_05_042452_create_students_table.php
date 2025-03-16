<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // คอลัมน์ ID อัตโนมัติ (Primary Key)
            $table->string('st_id')->unique(); // รหัสนักศึกษา (เช่น รหัสประจำตัว)
            $table->string('name'); // ชื่อนักศึกษา
            $table->enum('status', ['active', 'inactive'])->default('active'); // สถานะ (เช่น ใช้งาน/ไม่ใช้งาน)
            $table->timestamps(); // คอลัมน์ created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students'); // ลบตารางเมื่อ rollback
    }
}
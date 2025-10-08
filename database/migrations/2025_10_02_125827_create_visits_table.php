<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); 
            $table->dateTime('start_date');
            $table->enum('status', ['Check In', 'Check Out', 'Pending'])->default('Check In');
           $table->enum('patient_type', ['Inpatient', 'Outpatient'])->change();

            $table->boolean('scheduled')->default(false);
            $table->text('notes')->nullable();

            // Send To tab
            $table->string('department')->nullable();
            $table->string('practitioner')->nullable();
            $table->string('practitioner_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};

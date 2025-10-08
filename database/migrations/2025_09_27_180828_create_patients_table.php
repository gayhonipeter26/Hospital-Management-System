<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // database/migrations/xxxx_xx_xx_create_patients_table.php

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_code')->default('+254'); // Country Code
            $table->string('phone'); // Local number only
            $table->string('email')->nullable();
            $table->string('gender');
            $table->date('dob');
            $table->string('blood_type')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('id_number')->nullable()->change();
            $table->string('nationality');
            $table->string('residence');
            $table->string('city')->nullable();
            $table->string('country');
            $table->string('occupation')->nullable();
            $table->string('religion')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

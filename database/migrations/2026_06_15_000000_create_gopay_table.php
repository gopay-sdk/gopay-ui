<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('gopay', function (Blueprint $table) {
            $table->id();
            $table->string('myref')->unique();
            $table->string('ref')->unique()->nullable();
            $table->string('issaved')->default(0);
            $table->boolean('isfailed')->default(0);
            $table->longText('paydata');
            $table->longText('save_error')->nullable();
            $table->dateTime('date')->useCurrent();
            $table->string('environment', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gopay');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('gopay_form', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->double('amount');
            $table->string('currency', 3);
            $table->string('phone', 10)->nullable();
            $table->string('signature');
            $table->longText('payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gopay_form');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('lead_id')->unsigned();
            $table->string('street', 180);
            $table->string('city', 80);
            $table->string('state', 10);
            $table->string('zip', 10);
            $table->softDeletes();
            $table->foreign('id')->references('id')->on('leads')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

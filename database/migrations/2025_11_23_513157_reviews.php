<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("reviews", function (Blueprint $table) {
             $table->id();
             $table->float("reviewsAll");
             $table->unsignedBigInteger("user_id");
                $table->unsignedBigInteger("apartments_id");
             $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
              $table->foreign('apartments_id')->references('id')->on('apartments')->onDelete('cascade');
             $table->timestamps();         

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("reviews");
    }
};

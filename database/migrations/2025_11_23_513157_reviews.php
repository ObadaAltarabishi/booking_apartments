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
             $table->integer("count");
             $table->float("reviewsAll");
             $table->foreignId("user_id")->constrained()->cascadeOnDelete();
             $table->foreignId("apartments_id")->constrained()->cascadeOnDelete();  
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

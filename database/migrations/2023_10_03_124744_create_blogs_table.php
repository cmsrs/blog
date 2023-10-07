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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('publication_date')->index();
            $table->unsignedBigInteger('external_id')->nullable(); //->index();
            $table->unsignedBigInteger('user_id')->notNullable();
            $table->foreign('user_id')->notNullable()->references('id')->on('users');       
            //$table->index(['publication_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};

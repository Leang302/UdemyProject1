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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            //these are steps that foreighId do for us
            //we do this when we need to use the foreign key from the same table
            //in this case if we want to get userId we need to name it user_id which will not match our needs because there's two user_id colunmn
            // so we need to create our own foreign key
            //we need to specify reference of column and on('tablename')
            $table->unsignedBigInteger('followedUser'); 
            $table->foreign('followedUser')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};

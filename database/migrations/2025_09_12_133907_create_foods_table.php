<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) { // Đảm bảo là 'foods'
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
            $table->string('image');
            $table->foreignId('category_id')->constrained();
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('foods'); // Đảm bảo là 'foods'
    }
};
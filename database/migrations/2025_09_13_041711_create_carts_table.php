<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Đảm bảo sử dụng InnoDB engine
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_id')->constrained('foods')->onDelete('cascade'); // SỬA: 'foods' thay vì 'food'
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->unique(['user_id', 'food_id']);
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
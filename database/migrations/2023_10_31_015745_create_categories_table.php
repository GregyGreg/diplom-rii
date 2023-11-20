<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Автоинкрементируемый идентификатор
            $table->string('title'); // Название категории
            $table->text('description')->nullable(); // Описание категории (может быть пустым)
            $table->timestamps(); // Добавить поля created_at и updated_at для отслеживания времени создания и обновления записи
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};

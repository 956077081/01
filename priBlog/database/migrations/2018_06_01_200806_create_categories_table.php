<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id')->comment("主键id");
            $table->string('name',30)->comment('分类名称');
            $table->integer('parent_id')->comment('父级ID');
            $table->string('path','30')->comment('路径');
            $table->integer('status')->default(1)->comment("状态");
            $table->integer('sort')->default(1000)->comment("排序");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}

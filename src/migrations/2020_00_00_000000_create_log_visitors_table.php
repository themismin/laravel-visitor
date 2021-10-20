<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户ID');
            $table->unsignedBigInteger('fuid')->nullable()->comment('来源用户ID');

            $table->string('hittable_type')->nullable();
            $table->unsignedBigInteger('hittable_id')->nullable();

            $table->string('scene')->nullable()->comment('场景值');
            $table->string('path')->nullable()->comment('路径');
            $table->json('options')->nullable()->comment('参数');
            $table->integer('page')->default(0)->comment('分页');

            $table->string('ip', 50)->comment('IP地址');
            $table->string('country', 50)->nullable()->comment('国家');
            $table->string('city', 50)->nullable()->comment('城市');
            $table->integer('clicks')->unsigned()->default(0);
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `log_visitors` comment '访客记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_visitors');
    }
}

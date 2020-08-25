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
                $table->increments('id');
                $table->string('ip', 50)->comment('IP地址');
                $table->integer('user_id')->nullable()->comment('用户ID');
                $table->string('hittable_type')->nullable();
                $table->integer('hittable_id')->nullable();

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

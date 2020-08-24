<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;

    class CreateVisitorRegistry extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('visitor_registry', function (Blueprint $table) {
                $table->increments('id');
                $table->string('ip', 32);
                $table->string('country', 4)->nullable();
                
                $table->string('city', 20)->nullable();//新增

                $table->integer('clicks')->unsigned()->default(0);

                $table->integer('users_id')->nullable();//新增
                $table->integer('hittable_id')->nullable();//新增
                $table->string('hittable_type')->nullable();//新增
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
            Schema::drop('visitor_registry');
        }
    }

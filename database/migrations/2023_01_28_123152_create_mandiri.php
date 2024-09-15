<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMandiri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandiri', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5);
            $table->string('number', 10)->unique();
            $table->string('currency', 10);
            $table->string('name', 20);
            $table->string('periode');
            $table->string('jth_tempo');
            $table->string('open');
            $table->string('close');
            $table->string('bill');
            $table->datetime('updated_at')->useCurrent();
            $table->datetime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mandiri');
    }
}

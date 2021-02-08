<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('idpel');
            $table->bigInteger('no_meter');
            $table->string('nama', 200)->nullable(true);
            $table->string('alamat', 500)->nullable(true);
            $table->string('tarif', 10)->nullable(true);
            $table->decimal('daya', 8, 2)->nullable(true);
            $table->tinyInteger('krn_lama')->nullable(true);
            $table->tinyInteger('vkrn_lama')->nullable(true);
            $table->tinyInteger('krn')->nullable(true);
            $table->tinyInteger('vkrn')->nullable(true);
            $table->bigInteger('kct1a')->nullable(true);
            $table->bigInteger('kct1b')->nullable(true);
            $table->bigInteger('kct2a')->nullable(true);
            $table->bigInteger('kct2b')->nullable(true);
            $table->string('img', 200)->nullable(true);
            $table->boolean('upgraded')->nullable(true)->default(0);
            $table->dateTime('upgraded_at')->nullable(true);
            $table->boolean('confirmed')->nullable(true)->default(0);
            $table->dateTime('confirmed_at')->nullable(true);
            $table->bigInteger('confirmed_by')->nullable(true);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggans');
    }
}

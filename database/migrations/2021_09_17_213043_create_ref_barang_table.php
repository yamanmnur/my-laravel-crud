<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_barang', function (Blueprint $table) {
            $table->string('id',36)->primary();
            $table->string('kode',20);
            $table->string('nama',500);
            $table->bigInteger('kuantiti',false);
            $table->text('lokasi');
            $table->string('status',2);
            $table->string('satuan',200);
            $table->string('created_by',36)->nullable();
            $table->string('updated_by',36)->nullable();
            $table->string('deleted_by',36)->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('ref_barang');
    }
}

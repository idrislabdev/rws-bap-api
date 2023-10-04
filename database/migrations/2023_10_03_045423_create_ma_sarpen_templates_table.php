<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaSarpenTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_sarpen_templates', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nama')->unique();
            $table->enum('group', ['TELKOM', 'OTHER']);
            $table->enum('sto_site', ['STO', 'SITE', 'NO_ORDER']);
            $table->boolean('is_no_dokumen_klien');
            $table->json('tower')->nullable();
            $table->json('rack')->nullable();
            $table->json('ruangan')->nullable();
            $table->json('catu_daya_mcb')->nullable();
            $table->json('catu_daya_genset')->nullable();
            $table->json('service')->nullable();
            $table->json('akses')->nullable();
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
        Schema::dropIfExists('ma_sarpen_templates');
    }
}

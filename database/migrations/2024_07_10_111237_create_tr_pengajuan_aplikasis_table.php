<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrPengajuanAplikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_pengajuan_aplikasis', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_account_id');
            $table->foreign('user_account_id')->references('id')->on('ma_user_accounts');
            $table->string('site_witel')->nullable();
            $table->string('aplikasi'); // starclick_ncx, ncx_cons
            $table->json('profiles');
            $table->json('user_account_pengajuan');
            $table->enum('jenis_pengajuan', ['baru', 'reaktivasi', 'tambah_fitur', 'hapus']);
            $table->enum('status_pengajuan', ['proposed', 'approved', 'rejected', 'process', 'finished']);
            $table->dateTime('proposed_date');
            $table->string('proposed_by', 100)->nullable();
            $table->foreign('proposed_by')->references('id')->on('ma_penggunas');
            $table->json('proposed_by_data')->nullable();
            $table->string('rejected_by', 100)->nullable();
            $table->foreign('rejected_by')->references('id')->on('ma_penggunas');
            $table->json('rejected_by_data')->nullable();
            $table->string('approved_by', 100)->nullable();
            $table->foreign('approved_by')->references('id')->on('ma_penggunas');
            $table->json('approved_by_data')->nullable();
            $table->string('process_by', 100)->nullable();
            $table->foreign('process_by')->references('id')->on('ma_penggunas');
            $table->json('process_by_data')->nullable();
            $table->text('rejected_note')->nullable();
            $table->string('history_id')->nullable();
            $table->foreign('history_id')->references('id')->on('tr_history_pengajuans');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('tr_pengajuan_aplikasis');
    }
}

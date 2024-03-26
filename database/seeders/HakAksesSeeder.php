<?php

namespace Database\Seeders;

use App\Models\MaHakAkses;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class HakAksesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.dashboard', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.view', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.import', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.edit', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.create_ba', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.evidence', 'deskripsi' => '-'],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.upgrade', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.dual_homing', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.relokasi', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.dismantle', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.other_ba', 'deskripsi' => '-'],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.paraf_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.ttd_wholesale', 'deskripsi' => '-'],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.view', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.create', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.update', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.delete', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.paraf_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.ttd_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.report', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.report.view', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo.report.export', 'deskripsi' => '-'],


            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.dashboard', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.view', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.create', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.update', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.edit', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.reject', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.delete', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.proposed', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.ttd_witel', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.paraf_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.ttd_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.download', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.upload', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.bypass', 'deskripsi' => '-'],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.view', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.create', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.update', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.edit', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.reject', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.delete', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.proposed', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.ttd_witel', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.paraf_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.ttd_wholesale', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.download', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.upload', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other.bypass', 'deskripsi' => '-'],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.setting', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.target', 'deskripsi' => '-'],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'dokumen', 'deskripsi' => '-'],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'pengaturan', 'deskripsi' => '-'],
        ];

        foreach ($items as $item) {
            $hak_akases = MaHakAkses::where('nama', $item['nama'])->first();
            if (!$hak_akases) {
                MaHakAkses::create($item);
            }
        }
    }
}

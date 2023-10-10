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
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.dashboard', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.view', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.import', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.edit', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.create_ba', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.new_link.evidence', 'deskripsi' => '-' ],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.upgrade', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.dual_homing', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.relokasi', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.dismantle', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'cnop.other_ba', 'deskripsi' => '-' ],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'olo', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.dashboard', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.view', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.create', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.update', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.edit', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.delete', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.paraf', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.telkomsel.pejabat', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.other', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'sarpen.setting', 'deskripsi' => '-' ],

            ['id' => Uuid::uuid1()->toString(), 'nama' => 'dokumen', 'deskripsi' => '-' ],
            ['id' => Uuid::uuid1()->toString(), 'nama' => 'pengaturan', 'deskripsi' => '-' ],
        ];

        foreach ($items as $item) {
            $hak_akases = MaHakAkses::where('nama',$item['nama'])->first();
            if (!$hak_akases) {
                MaHakAkses::create($item);
            }
        }
     
    }
}

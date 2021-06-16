<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DualHomingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'wo_id' => $this->wo_id,
            'site_id' => $this->site_id,
            'wo_id' => $this->wo_id,
            'wo_site_id' => $this->wo_site_id,
            'site_id' => $this->site_id,
            'site_name' => $this->site_name,
            'site_witel' => $this->site_witel,
            'tsel_reg' => $this->tsel_reg,
            'tgl_on_air' => $this->tgl_on_air,
            'jumlah' => $this->jumlah,
            'status' => $this->status,
            'progress' => $this->progress,
            'ba_id' => $this->ba_id,
            'topologi' => $this->topologi,
            'konfigurasi_node_1' => $this->konfigurasi_node_1,
            'konfigurasi_node_2' => $this->konfigurasi_node_2,
            'pr_dual_homing' => $this->pr_dual_homing,
            'node_1' => $this->node_1,
            'node_2' => $this->node_2,
            'sto_a' => $this->sto_a,
            'sto_b' => $this->sto_b,
            'keterangan' => $this->keterangan,
            'jenis_node' => 'GPON',//$this->jenis_node,
            'dibuat_oleh' => $this->dibuat_oleh,
            'tipe_ba' => $this->tipe_ba,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pengguna_id' => $this->pengguna_id,
            'nama_lengkap' => $this->nama_lengkap,
            'no_dokumen' => $this->no_dokumen
        ];
    }
}

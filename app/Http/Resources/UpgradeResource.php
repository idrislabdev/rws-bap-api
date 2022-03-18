<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UpgradeResource extends JsonResource
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
            'dasar_order' => $this->dasar_order,
            'tahun_order' => $this->tahun_order,
            'lampiran_url' => $this->lampiran_url,
            'site_id' => $this->site_id,
            'site_name' => $this->site_name,
            'site_witel' => $this->site_witel,
            'tsel_reg' => $this->tsel_reg,
            'tgl_on_air' => $this->tgl_on_air,
            'data_2g' => $this->data_2g,
            'data_3g' => $this->data_3g,
            'data_4g' => $this->data_4g,
            'jumlah' => $this->jumlah,
            'program' => $this->program,
            'status' => $this->status,
            'ba_id' => $this->ba_id,
            'konfigurasi' => $this->konfigurasi,
            'topologi' => $this->topologi,
            'capture_trafik' => $this->capture_trafik,
            'dibuat_oleh' => $this->dibuat_oleh,
            'tipe_ba' => $this->tipe_ba,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pengguna_id' => $this->pengguna_id,
            'nama_lengkap' => $this->nama_lengkap,
            'no_dokumen' => $this->no_dokumen,
            'alpro_site' => $this->alpro_site
        ];
    }
}

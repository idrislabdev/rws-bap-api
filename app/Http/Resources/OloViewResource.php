<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OloViewResource extends JsonResource
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
            'id' => $this->id,
            'olo_ba_id' => $this->olo_ba_id,
            'alamat_instalasi' => $this->alamat_instalasi,
            'ao_sc_order' => $this->ao_sc_order,
            'approved_by' => $this->approved_by,
            'bandwidth_mbps' => $this->bandwidth_mbps,
            'dibuat_oleh' => $this->dibuat_oleh,
            'jenis_order' => $this->jenis_order,
            'jenis_order_id' => $this->jenis_order_id,
            'klien_id' => $this->klien_id,
            'klien_jabatan_penanggung_jawab_baut' => $this->klien_jabatan_penanggung_jawab_baut,
            'klien_lokasi_kerja_baut' => $this->klien_lokasi_kerja_baut,
            'klien_nama_baut' => $this->klien_nama_baut,
            'klien_penanggung_jawab_baut' => $this->klien_penanggung_jawab_baut,
            'no_dokumen_bast' => $this->no_dokumen_bast,
            'no_dokumen_baut' => $this->no_dokumen_baut,
            'produk' => $this->produk,
            'produk_id' => $this->produk_id,
            'sid' => $this->sid,
            'status_approval' => $this->status_approval,
            'tgl_dokumen' => $this->tgl_dokumen,
            'tgl_order' => $this->tgl_order,
            'add_ons' => $this->add_ons,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

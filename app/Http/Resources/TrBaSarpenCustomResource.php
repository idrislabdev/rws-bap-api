<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrBaSarpenCustomResource extends JsonResource
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
            "id" => $this->id,
            "no_dokumen" => $this->no_dokumen,
            "no_dokumen_klien" => $this->no_dokumen_klien,
            "tanggal_buat" => $this->tanggal_buat,
            "group" => $this->group,
            "type" => $this->type,
            "site" => $this->site,
            "sto" => $this->sto,
            "nama_site" => $this->nama_site,
            "nama_sto" => $this->nama_sto,
            "nomor_order" => $this->nomor_order,
            "alamat" => $this->alamat,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "regional" => $this->regional,
            "nama_klien" => $this->nama_klien,
            "klien" => $this->klien,
            "klien_data" => json_decode($this->klien_data),
            "manager_witel" => $this->manager_witel,
            "manager_witel_data" => json_decode($this->manager_witel_data),
            "paraf_wholesale" => $this->paraf_wholesale,
            "paraf_wholesale_data" => json_decode($this->paraf_wholesale_data),
            "manager_wholesale" => $this->manager_wholesale,
            "manager_wholesale_data" => json_decode($this->manager_wholesale_data),
            "manager_wholesale_data" => json_decode($this->manager_wholesale_data),
            "catatan" => $this->catatan,
            "status" => $this->status,
            "site_witel" => $this->site_witel,
            "dokumen_sirkulir" => $this->dokumen_sirkulir,
            "setting" => json_decode($this->setting),
            "created_by" => $this->created_by,
            "pembuat" => $this->pembuat,
            // "ne_iptvs" => $this->neIptvs,
            "ne_iptvs_count" => $this->ne_iptvs_count,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}

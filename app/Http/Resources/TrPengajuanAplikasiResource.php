<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrPengajuanAplikasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "aplikasi" => $this->aplikasi,
            "approved_by" => $this->approvedBy,
            "created_at" => $this->created_at,
            "keterangan" => $this->keterangan,
            "process_by" => $this->processBy,
            "proposed_by" => $this->proposedBy,
            "proposed_date" => $this->proposed_date,
            "rejected_by" => $this->rejected_by,
            "rejected_note" => $this->rejected_note,
            "site_witel" => $this->site_witel,
            "user_account" => $this->userAccount,
            "user_account_id" => $this->user_account_id,
            "user_account_pengajuan" => json_decode($this->user_account_pengajuan),
            "profiles" => json_decode($this->profiles),
            "jenis_pengajuan" => $this->jenis_pengajuan,
            "status_pengajuan" => $this->status_pengajuan,
            "account_profile" => $this->accountProfile,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaJabatanResource extends JsonResource
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
            "nama" => $this->nama,
            "starclick_ncx" => json_decode($this->starclick_ncx),
            "ncx_cons" => json_decode($this->ncx_cons),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}

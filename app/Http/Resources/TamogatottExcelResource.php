<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TamogatottExcelResource extends JsonResource
{
    public static function getHeader() {
        return [
            'ID',
            'Név',
            'K-url',
            'Ir.szám',
            'Város',
            'Utca'
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            $this->id,
            $this->name,
            $this->kurl,
            $this->irszam,
            $this->varos,
            $this->utca
        ];
    }
}

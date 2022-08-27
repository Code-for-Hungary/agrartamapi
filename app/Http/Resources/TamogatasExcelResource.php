<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TamogatasExcelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            ['col' => 'A', 'data' => $this->id],
            ['col' => 'B', 'data' => $this->ev],
            ['col' => 'C', 'data' => $this->name],
            ['col' => 'D', 'data' => $this->gender],
            ['col' => 'E', 'data' => $this->is_firm],
            ['col' => 'F', 'data' => $this->irszam],
            ['col' => 'G', 'data' => $this->varos],
            ['col' => 'H', 'data' => $this->utca],
            ['col' => 'I', 'data' => $this->megye_id],
            ['col' => 'J', 'data' => $this->megye?->name],
            ['col' => 'K', 'data' => $this->cegcsoport_id],
            ['col' => 'L', 'data' => $this->cegcsoport?->name],
            ['col' => 'M', 'data' => $this->tamogatott_id],
            ['col' => 'N', 'data' => $this->tamogatott?->name],
            ['col' => 'O', 'data' => $this->jogcim_id],
            ['col' => 'P', 'data' => $this->jogcim?->name],
            ['col' => 'Q', 'data' => $this->alap_id],
            ['col' => 'R', 'data' => $this->alap?->name],
            ['col' => 'S', 'data' => $this->forras_id],
            ['col' => 'T', 'data' => $this->forras?->name],
            ['col' => 'U', 'data' => $this->is_landbased],
            ['col' => 'V', 'data' => $this->osszeg]
        ];
    }

    public static function getHeader()
    {
        return [
            ['col' => 'A', 'data' => 'ID'],
            ['col' => 'B', 'data' => 'Év'],
            ['col' => 'C', 'data' => 'Nyertes neve'],
            ['col' => 'D', 'data' => 'Gender'],
            ['col' => 'E', 'data' => 'Jogi személy'],
            ['col' => 'F', 'data' => 'Ir.szám'],
            ['col' => 'G', 'data' => 'Város'],
            ['col' => 'H', 'data' => 'Utca'],
            ['col' => 'I', 'data' => 'Megye ID'],
            ['col' => 'J', 'data' => 'Megye'],
            ['col' => 'K', 'data' => 'Cégcsoport ID'],
            ['col' => 'L', 'data' => 'Cégcsoport'],
            ['col' => 'M', 'data' => 'Tám. entitás ID'],
            ['col' => 'N', 'data' => 'Tám. entitás'],
            ['col' => 'O', 'data' => 'Jogcím ID'],
            ['col' => 'P', 'data' => 'Jogcím'],
            ['col' => 'Q', 'data' => 'Alap ID'],
            ['col' => 'R', 'data' => 'Alap'],
            ['col' => 'S', 'data' => 'Forrás ID'],
            ['col' => 'T', 'data' => 'Forrás'],
            ['col' => 'U', 'data' => 'Föld. alapú'],
            ['col' => 'V', 'data' => 'Összeg']
        ];
    }
}

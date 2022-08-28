<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TamogatasResource extends JsonResource
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
            'id' => $this->id,
            'ev' => $this->ev,
            'name' => $this->name,
            'irszam' => $this->irszam,
            'varos' => $this->varos,
            'utca' => $this->utca,
            'osszeg' => $this->osszeg,
            'evesosszeg' => $this->evesosszeg,
            'is_firm' => $this->is_firm,
            'is_landbased' => $this->is_landbased,
            'gender' => $this->gender,
            'point_lat' => $this->point_lat,
            'point_long' => $this->point_long,
            'megye' => $this->whenLoaded('megye'),
            'cegcsoport' => $this->whenLoaded('cegcsoport'),
            'tamogatott' => $this->whenLoaded('tamogatott'),
            'jogcim' => $this->whenLoaded('jogcim'),
            'alap' => $this->whenLoaded('alap'),
            'forras' => $this->whenLoaded('forras')
        ];
    }
}

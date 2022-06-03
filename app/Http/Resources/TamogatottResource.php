<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TamogatottResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ret = parent::toArray($request);

        $cim = '';
        if ($this->irszam && $this->varos) {
            $cim = $this->irszam . ' ' . $this->varos;
        }
        if ($cim && $this->utca) {
            $cim .= ', ' . $this->utca;
        }
        $ret['cim'] = $cim;
        return $ret;
    }
}

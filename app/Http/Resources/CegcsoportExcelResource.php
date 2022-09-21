<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CegcsoportExcelResource extends JsonResource
{
    public static function getHeader() {
        return [
            'ID',
            'Név'
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
            $this->name
        ];
    }
}

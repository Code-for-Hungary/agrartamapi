<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FileNameTrait
{

    public function getFileName($pre = 'agrar_') {
        $fname = $pre . Str::uuid() . '.xlsx';
        return [
            'filename' => $fname,
            'fullfilename' => public_path('storage/') . $fname
        ];
    }
}

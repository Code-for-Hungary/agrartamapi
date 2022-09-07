<?php

namespace App\Http\Controllers;

use App\Models\Tamogatas;
use Illuminate\Http\Request;

class EvesTamogatasOsszegController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $min = Tamogatas::min('evesosszeg') / 1000;
        $max = Tamogatas::max('evesosszeg') / 1000;
        return response()->json([
                                    'data' => [
                                        'min' => $min,
                                        'max' => $max
                                    ]
                                ]);
    }
}

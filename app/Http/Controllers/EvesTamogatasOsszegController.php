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
        $min = Tamogatas::min('evesosszeg');
        $max = Tamogatas::max('evesosszeg');
        return response()->json([
                                    'data' => [
                                        'min' => $min,
                                        'max' => $max
                                    ]
                                ]);
    }
}

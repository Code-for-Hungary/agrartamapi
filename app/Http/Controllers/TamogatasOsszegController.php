<?php

namespace App\Http\Controllers;

use App\Models\Tamogatas;
use Illuminate\Http\Request;

class TamogatasOsszegController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $min = Tamogatas::min('osszeg');
        $max = Tamogatas::max('osszeg');
        return response()->json([
                                    'data' => [
                                        'min' => $min,
                                        'max' => $max
                                    ]
                                ]);
    }
}

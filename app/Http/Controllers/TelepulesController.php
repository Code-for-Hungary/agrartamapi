<?php

namespace App\Http\Controllers;

use App\Http\Resources\TelepulesResource;
use App\Models\Telepules;
use Illuminate\Http\Request;

class TelepulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TelepulesResource::collection(Telepules::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Telepules  $telepules
     * @return \Illuminate\Http\Response
     */
    public function show(Telepules $telepules)
    {
        return new TelepulesResource($telepules);
    }

}

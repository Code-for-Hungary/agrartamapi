<?php
/**
 * @apiDefine JogcimResponse
 * @apiSuccess {number} id Jogcím's unique id
 * @apiSuccess {string} name Jogcím's name
 * @apiSuccessExample Success-Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "data": {
 *              "id": 1,
 *              "name": "Nemzeti"
 *          }
 *      }
 */

namespace App\Http\Controllers;

use App\Http\Resources\JogcimResource;
use App\Models\Jogcim;
use Illuminate\Http\Request;

class JogcimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @api {get} /jogcim Request Jogcím index
     * @apiSampleRequest off
     * @apiName GetJogcímIndex
     * @apiGroup Jogcím
     * @apiSuccess {Object[]} data List of jogcím
     * @apiSuccess {number} id Jogcím's unique id
     * @apiSuccess {string} name Jogcím's name
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data": [
     *              {
     *                  "id": 1,
     *                  "name": "Nemzeti"
     *              },
     *              {
     *                  "id": 2,
     *                  "name": "EU"
     *              }
     *          ]
     *      }
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JogcimResource::collection(Jogcim::select()->orderBy('name', 'asc')->get());
    }


    /**
     * Display the specified resource.
     *
     * @api {get} /jogcims/:id Request jogcím information
     * @apiSampleRequest off
     * @apiName GetJogcím
     * @apiGroup Jogcím
     * @apiParam (url) {string} id Jogcím's unique id
     * @apiUse JogcimResponse
     *
     * @param  \App\Models\Jogcim  $jogcim
     * @return \Illuminate\Http\Response
     */
    public function show(Jogcim $jogcim)
    {
        return new JogcimResource($jogcim);
    }

}

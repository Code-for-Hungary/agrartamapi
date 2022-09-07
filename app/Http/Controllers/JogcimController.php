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
     * @return \Illuminate\Http\Response
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
     */
    public function index()
    {
        return JogcimResource::collection(
            Jogcim::select()
                ->orderBy('sorrend', 'asc')
                ->orderBy('name', 'asc')
                ->get()
        );
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Jogcim $jogcim
     * @return \Illuminate\Http\Response
     * @api {get} /jogcims/:id Request jogcím information
     * @apiSampleRequest off
     * @apiName GetJogcím
     * @apiGroup Jogcím
     * @apiParam (url) {string} id Jogcím's unique id
     * @apiUse JogcimResponse
     *
     */
    public function show(Jogcim $jogcim)
    {
        return new JogcimResource($jogcim);
    }

}

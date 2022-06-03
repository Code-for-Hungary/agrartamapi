<?php
/**
 * @apiDefine AlapResponse
 * @apiSuccess {number} id Alap's unique id
 * @apiSuccess {string} name Alap's name
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

use App\Http\Resources\AlapResource;
use App\Models\Alap;
use Illuminate\Http\Request;

class AlapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @api {get} /alaps Request Alap index
     * @apiSampleRequest off
     * @apiName GetAlapIndex
     * @apiGroup Alap
     * @apiSuccess {Object[]} data List of alaps
     * @apiSuccess {number} id Alap's unique id
     * @apiSuccess {string} name Alap's name
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
        return AlapResource::collection(Alap::all());
    }

    /**
     * Display the specified resource.
     *
     * @api {get} /alaps/:id Request alap information
     * @apiSampleRequest off
     * @apiName GetAlap
     * @apiGroup Alap
     * @apiParam (url) {string} id Alap's unique id
     * @apiUse AlapResponse
     *
     * @param  \App\Models\Alap  $alap
     * @return \Illuminate\Http\Response
     */
    public function show(Alap $alap)
    {
        return new AlapResource($alap);
    }

}

<?php
/**
 * @apiDefine MegyeResponse
 * @apiSuccess {string} id Megye's unique id
 * @apiSuccess {string} name Megye's name
 * @apiSuccessExample Success-Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "data": {
 *              "id": "02",
 *              "name": "Baranya"
 *          }
 *      }
 */

namespace App\Http\Controllers;

use App\Http\Resources\MegyeResource;
use App\Models\Megye;
use Illuminate\Http\Request;

class MegyeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @api {get} /megyes Request Megye index
     * @apiSampleRequest off
     * @apiName GetMegyeIndex
     * @apiGroup Megye
     * @apiSuccess {Object[]} data List of megyes
     * @apiSuccess {string} id Megye's unique id
     * @apiSuccess {string} name Megye's name
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data": [
     *              {
     *                  "id": "01",
     *                  "name": "Budapest"
     *              },
     *              {
     *                  "id": "02",
     *                  "name": "Baranya"
     *              }
     *          ]
     *      }
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MegyeResource::collection(Megye::all());
    }

    /**
     * Display the specified resource.
     *
     * @api {get} /megyes/:id Request megye information
     * @apiSampleRequest off
     * @apiName GetMegye
     * @apiGroup Megye
     * @apiParam (url) {string} id Megye's unique id
     * @apiUse MegyeResponse

     * @param  \App\Models\Megye  $megye
     * @return \Illuminate\Http\Response
     */
    public function show(Megye $megye)
    {
        return new MegyeResource($megye);
    }

}

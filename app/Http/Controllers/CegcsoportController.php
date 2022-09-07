<?php
/**
 * @apiDefine CegcsoportResponse
 * @apiSuccess {string} id Cégcsoport's unique id
 * @apiSuccess {string} name Cégcsoport's name
 * @apiSuccessExample Success-Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "data": {
 *              "id": "lt000001",
 *              "name": "Mészáros Group"
 *          }
 *      }
 */

namespace App\Http\Controllers;

use App\Http\Resources\CegcsoportResource;
use App\Models\Cegcsoport;
use Illuminate\Http\Request;

class CegcsoportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @api {get} /cegcsoports Request Cégcsoport index
     * @apiSampleRequest off
     * @apiName GetCégcsoportIndex
     * @apiGroup Cégcsoport
     * @apiSuccess {Object[]} data List of cégcsoports
     * @apiSuccess {string} id Cégcsoport's unique id
     * @apiSuccess {string} name Cégcsoport's name
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data": [
     *              {
     *                  "id": "lt000001",
     *                  "name": "Mészáros Csoport"
     *              },
     *              {
     *                  "id": "fl00002",
     *                  "name": "Flier Csoport"
     *              }
     *          ]
     *      }
     *
     */
    public function index()
    {
        return CegcsoportResource::collection(Cegcsoport::all());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Cegcsoport $cegcsoport
     * @return \Illuminate\Http\Response
     * @api {get} /cegcsoports/:id Request cégcsoport information
     * @apiSampleRequest off
     * @apiName GetCégcsoport
     * @apiGroup Cégcsoport
     * @apiParam (url) {string} id Cégcsoport's unique id
     * @apiUse CegcsoportResponse
     *
     */
    public function show(Cegcsoport $cegcsoport)
    {
        return new CegcsoportResource($cegcsoport);
    }

}

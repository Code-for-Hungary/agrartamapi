<?php
/**
 * @apiDefine ForrasResponse
 * @apiSuccess {number} id Forrás's unique id
 * @apiSuccess {string} name Forrás's name
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

use App\Http\Resources\ForrasResource;
use App\Models\Forras;
use Illuminate\Http\Request;

class ForrasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @api {get} /forras Request Forrás index
     * @apiSampleRequest off
     * @apiName GetForrásIndex
     * @apiGroup Forrás
     * @apiSuccess {Object[]} data List of forrás
     * @apiSuccess {number} id Forrás's unique id
     * @apiSuccess {string} name Forrás's name
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
        return ForrasResource::collection(Forras::select()->orderBy('name', 'asc')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Forras $forras
     * @return \Illuminate\Http\Response
     * @api {get} /forras/:id Request forrás information
     * @apiSampleRequest off
     * @apiName GetForrás
     * @apiGroup Forrás
     * @apiParam (url) {string} id Forrás's unique id
     * @apiUse ForrasResponse
     *
     */
    public function show(Forras $forras)
    {
        return new ForrasResource($forras);
    }

}

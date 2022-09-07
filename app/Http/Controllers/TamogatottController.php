<?php
/**
 * @apiDefine TamogatottResponse
 * @apiSuccess {string} id Támogatott's unique id
 * @apiSuccess {string} name Támogatott's name
 * @apiSuccessExample Success-Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "data": {
 *              "id": "lt000001",
 *              "name": "Mészáros Lőrinc"
 *          }
 *      }
 */

namespace App\Http\Controllers;

use App\Http\Resources\TamogatottResource;
use App\Models\Tamogatott;
use Illuminate\Http\Request;

class TamogatottController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @api {get} /tamogatotts Request Támogatott index
     * @apiSampleRequest off
     * @apiName GetTámogatottIndex
     * @apiGroup Támogatott
     * @apiSuccess {Object[]} data List of támogatotts
     * @apiSuccess {string} id Támogatott's unique id
     * @apiSuccess {string} name Támogatott's name
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data": [
     *              {
     *                  "id": "lt000001",
     *                  "name": "Mészáros Lőrinc"
     *              },
     *              {
     *                  "id": "fl00002",
     *                  "name": "Flier Valaki"
     *              }
     *          ]
     *      }
     *
     */
    public function index()
    {
        return TamogatottResource::collection(Tamogatott::select()->orderBy('name', 'asc')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Tamogatott $tamogatott
     * @return \Illuminate\Http\Response
     * @api {get} /tamogatotts/:id Request támogatott information
     * @apiSampleRequest off
     * @apiName GetTámogatott
     * @apiGroup Támogatott
     * @apiParam (url) {string} id Támogatott's unique id
     * @apiUse TamogatottResponse
     *
     */
    public function show(Tamogatott $tamogatott)
    {
        return new TamogatottResource($tamogatott);
    }

}

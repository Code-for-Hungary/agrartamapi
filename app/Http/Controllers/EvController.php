<?php
/**
 * @apiDefine EvResponse
 * @apiSuccess {string} ev Év
 * @apiSuccessExample Success-Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "data": [
 *              {
 *                  "ev": "2011"
 *              },
 *              {
 *                  "ev": "2012"
 *              }
 *          ]
 *      }
 */

namespace App\Http\Controllers;

use App\Http\Resources\EvResource;
use App\Models\Tamogatas;
use Illuminate\Http\Request;

class EvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @api {get} /evs Request Év index
     * @apiSampleRequest off
     * @apiName GetÉvIndex
     * @apiGroup Év
     * @apiSuccess {Object[]} data List of évs
     * @apiSuccess {string} ev Év
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data": [
     *              {
     *                  "ev": "2011"
     *              },
     *              {
     *                  "ev": "2012"
     *              }
     *          ]
     *      }
     *
     */
    public function index()
    {
        return EvResource::collection(Tamogatas::select(['ev'])->distinct(['ev'])->orderBy('ev', 'desc')->get());
    }
}

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

use App\Http\Resources\AlapExcelResource;
use App\Http\Resources\AlapResource;
use App\Models\Alap;
use App\Traits\FileNameTrait;
use Illuminate\Http\Request;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class AlapController extends Controller
{
    use FileNameTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     */
    public function index()
    {
        return AlapResource::collection(
            Alap::select()
                ->orderBy('sorrend', 'asc')
                ->orderBy('name', 'asc')
                ->get()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Alap $alap
     * @return \Illuminate\Http\Response
     * @api {get} /alaps/:id Request alap information
     * @apiSampleRequest off
     * @apiName GetAlap
     * @apiGroup Alap
     * @apiParam (url) {string} id Alap's unique id
     * @apiUse AlapResponse
     *
     */
    public function show(Alap $alap)
    {
        return new AlapResource($alap);
    }

    public function export(Request $request) {
        $writer = new Writer();
        $filename = $this->getFileName('alap_');
        $writer->openToBrowser($filename['filename']);

        $writer->addRow(Row::fromValues(AlapExcelResource::getHeader()));

        Alap::all()->each(function ($cegcs) use ($request, $writer) {
            $data = (new AlapExcelResource($cegcs))->toArray($request);
            $writer->addRow(Row::fromValues($data));
        });
        $writer->close();
    }

}

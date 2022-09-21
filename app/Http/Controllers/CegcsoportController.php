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

use App\Http\Resources\CegcsoportExcelResource;
use App\Http\Resources\CegcsoportResource;
use App\Models\Cegcsoport;
use App\Traits\FileNameTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class CegcsoportController extends Controller
{
    use FileNameTrait;

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
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
     * @return CegcsoportResource
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

    public function export(Request $request) {
        $writer = new Writer();
        $filename = $this->getFileName('cegcsoport_');
        $writer->openToBrowser($filename['filename']);

        $writer->addRow(Row::fromValues(CegcsoportExcelResource::getHeader()));

        Cegcsoport::all()->each(function ($cegcs) use ($request, $writer) {
            $data = (new CegcsoportExcelResource($cegcs))->toArray($request);
            $writer->addRow(Row::fromValues($data));
        });
        $writer->close();
    }
}

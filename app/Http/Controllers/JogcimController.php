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

use App\Http\Resources\JogcimExcelResource;
use App\Http\Resources\JogcimResource;
use App\Models\Jogcim;
use App\Traits\FileNameTrait;
use Illuminate\Http\Request;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class JogcimController extends Controller
{
    use FileNameTrait;

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

    public function export(Request $request) {
        $writer = new Writer();
        $filename = $this->getFileName('jogcim_');
        $writer->openToBrowser($filename['filename']);

        $writer->addRow(Row::fromValues(JogcimExcelResource::getHeader()));

        Jogcim::all()->each(function ($cegcs) use ($request, $writer) {
            $data = (new JogcimExcelResource($cegcs))->toArray($request);
            $writer->addRow(Row::fromValues($data));
        });
        $writer->close();
    }

}

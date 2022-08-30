<?php
/**
 * @apiDefine SearchResponse
 * @apiSuccess {string}
 * @apiSuccessExample Success-Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "data": [
 *              {
 *              },
 *              {
 *              }
 *          ]
 *      }
 */

namespace App\Http\Controllers;

use App\Http\Resources\CountResource;
use App\Http\Resources\TamogatasExcelResource;
use App\Http\Resources\TamogatasResourceCollection;
use App\Models\Tamogatas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SearchController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeQuery(Request $request) {
        $q = Tamogatas::with(['megye', 'cegcsoport', 'tamogatott', 'jogcim', 'alap', 'forras']);

        // ha cég nem 'mindegy'
        if ($request->isfirm === '0' || $request->isfirm === '1') {
            $q->where('is_firm', $request->isfirm);
        }
        // ha 'nem cég' és gender nem 'mindegy'
        if ($request->isfirm === '0' && $request->gender) {
            $q->where('gender', $request->gender);
        }
        if ($request->nev) {
            $q->whereFullText('name', $request->nev, ['mode' => 'boolean']);
        }
        if ($request->ev && is_array($request->ev)) {
            $q->whereIn('ev', $request->ev);
        }
        if ($request->irszam) {
            $q->where('irszam', 'like', $request->irszam);
        }
        if ($request->varos) {
            $q->where('varos', 'like', $request->varos);
        }
        if ($request->megye) {
            $q->where('megye_id', $request->megye);
        }
        if ($request->jogcim && is_array($request->jogcim)) {
            $q->whereIn('jogcim_id', $request->jogcim);
        }
        if ($request->alap && is_array($request->alap)) {
            $q->whereIn('alap_id', $request->alap);
        }
        if ($request->forras && is_array($request->forras)) {
            $q->whereIn('forras_id', $request->forras);
        }
        if ($request->cegcsoport && is_array($request->cegcsoport)) {
            $q->whereIn('cegcsoport_id', $request->cegcsoport);
        }
        if ($request->tamogatott) {
            $q->where('tamogatott_id', $request->tamogatott);
        }
        if (!is_null($request->tamosszegtol)) {
            $q->where('osszeg', '>=', $request->tamosszegtol);
        }
        if (!is_null($request->tamosszegig)) {
            $q->where('osszeg', '<=', $request->tamosszegig);
        }
        if (!is_null($request->evestamosszegtol)) {
            $q->where('evesosszeg', '>=', $request->evestamosszegtol);
        }
        if (!is_null($request->evestamosszegig)) {
            $q->where('evesosszeg', '<=', $request->evestamosszegig);
        }

        return $q;
    }

    /**
     * Display a listing of the resource.
     *
     * @api {get} /search Request
     * @apiSampleRequest off
     * @apiName Get
     * @apiGroup
     * @apiSuccess {Object[]} data List of
     * @apiSuccess {string}
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data": [
     *              {
     *              },
     *              {
     *              }
     *          ]
     *      }
     *
     */
    public function index(Request $request)
    {
        return new TamogatasResourceCollection($this->makeQuery($request)->paginate($request->per_page));
    }

    public function count(Request $request) {
        $ret = new CountResource($this->makeQuery($request)->count());
        return $ret;
    }

    public function exportforedit(Request $request) {
        $excel = new Spreadsheet();

        $excel->getProperties()
            ->setCreator('K-Monitor Agrártámogatás projekt')
            ->setTitle('Agrártámogatás adatbázis részlet')
            ->setDescription('Agrártámogatás adatbázis részlet');

        $sheet = $excel->setActiveSheetIndex(0);
        foreach (TamogatasExcelResource::getHeader() as $head) {
            $sheet->setCellValue($head['col'] . '1', $head['data']);
        }

        $sor = 2;
        $this->makeQuery($request)->lazy()->each(function ($tam) use ($request, $sheet, &$sor) {
            $resource = new TamogatasExcelResource($tam);
            $res = $resource->toArray($request);
            foreach ($res as $item) {
                $sheet->setCellValue($item['col'] . $sor, $item['data']);
            }
            $sor++;
        });
        $excel->getActiveSheet()->getStyle('S1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filename = 'agrar_' . Str::uuid() . '.xlsx';
        $pathfilename = public_path('storage/') . $filename;
        $writer->save($pathfilename);

        return [
            'data' => asset('storage/' . $filename, true)
        ];
    }
}

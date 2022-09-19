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
use App\Models\Kereseslog;
use App\Models\Tamogatas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class SearchController extends Controller
{

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
        $log = Kereseslog::fromRequest($request, 'search');
        DB::enableQueryLog();

        $ret = new TamogatasResourceCollection($this->makeQuery($request)->paginate($request->per_page));

        DB::disableQueryLog();
        $sqllog = DB::getQueryLog();
        DB::flushQueryLog();
        if (array_key_exists(1, $sqllog)) {
            $log->fillSqlAndSave($sqllog[1]);
        }
        return $ret;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeQuery(Request $request)
    {
        $q = Tamogatas::with(['megye', 'cegcsoport', 'tamogatott', 'jogcim', 'alap', 'forras', 'telepules']);

        // ha cég nem 'mindegy'
        if ($request->isfirm === '0' || $request->isfirm === '1') {
            $q->where('is_firm', $request->isfirm);
        }
        // ha 'nem cég' és gender nem 'mindegy'
        if ($request->isfirm === '0' && $request->gender) {
            $q->where('gender', $request->gender);
        }
        $nev = $this->preprocessNameFilter($request->nev);
        if ($nev) {
            $q->whereFullText('name', $nev, ['mode' => 'boolean']);
        }
        if ($request->ev && is_array($request->ev)) {
            $q->whereIn('ev', $request->ev);
        }
        if ($request->megye) {
            $q->where('megye_id', $request->megye);
        }
        if ($request->telepules && is_array($request->telepules)) {
            $q->whereIn('telepules_id', $request->telepules);
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

    protected function preprocessNameFilter($name)
    {
        $name = trim(mb_ereg_replace('([()])', '', $name));
        if ($name) {
            if (!mb_ereg('(["+\-~*])', $name)) {
                return '+' . mb_ereg_replace('([\s])', ' +', $name);
            }
            return $name;
        }
        return null;
    }

    public function count(Request $request)
    {
        $log = Kereseslog::fromRequest($request, 'count');
        DB::enableQueryLog();

        $ret = new CountResource($this->makeQuery($request)->count());

        DB::disableQueryLog();
        $sqllog = DB::getQueryLog();
        DB::flushQueryLog();
        $log->fillSqlAndSave($sqllog[0]);

        return $ret;
    }

    public function exportforedit(Request $request)
    {

        $writer = new Writer();
        $filename = 'agrar_' . Str::uuid() . '.xlsx';
        $pathfilename = public_path('storage/') . $filename;
        $writer->openToFile($pathfilename);

        $cellvalues = [];
        foreach (TamogatasExcelResource::getHeader() as $head) {
            $cellvalues[] = $head['data'];
        }
        $writer->addRow(Row::fromValues($cellvalues));

        $log = Kereseslog::fromRequest($request, 'exportforedit');
        DB::enableQueryLog();

        $this->makeQuery($request)->lazy()->each(function ($tam) use ($request, $writer) {
            $resource = new TamogatasExcelResource($tam);
            $res = $resource->toArray($request);
            $cellvalues = [];
            foreach ($res as $item) {
                $cellvalues[] = $item['data'];
            }
            $writer->addRow(Row::fromValues($cellvalues));
        });

        DB::disableQueryLog();
        $sqllog = DB::getQueryLog();
        DB::flushQueryLog();
        $log->fillSqlAndSave($sqllog[0]);

        $writer->close();
        return [
            'data' => asset('storage/' . $filename, true)
        ];
    }
}

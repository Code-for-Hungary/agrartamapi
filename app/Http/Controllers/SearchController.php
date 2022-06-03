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
use App\Models\Tamogatas;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected function makeQuery($request) {
        $q = Tamogatas::select();
        // ha cég nem 'mindegy'
        if ($request->isfirm === '0' || $request->isfirm === '1') {
            $q->where('is_firm', $request->isfirm);
        }
        // ha 'nem cég' és gender nem 'mindegy'
        if ($request->isfirm === '0' && $request->gender) {
            $q->where('gender', $request->gender);
        }
        if ($request->nev) {
            $q->whereFullText('name', $request->nev);
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
        if ($request->alap) {
            $q->where('alap_id', $request->alap);
        }
        if ($request->forras) {
            $q->where('forras_id', $request->forras);
        }
        if ($request->cegcsoport && is_array($request->cegcsoport)) {
            $q->whereIn('cegcsoport_id', $request->cegcsoport);
        }
        if ($request->tamogatott) {
            $q->where('tamogatott_id', $request->tamogatott);
        }
        if ($request->tamosszegtol) {
            $q->where('osszeg', '>=', $request->tamosszegtol);
        }
        if ($request->tamosszegig) {
            $q->where('osszeg', '<=', $request->tamosszegig);
        }
        if ($request->evestamosszegtol) {
            $q->where('evesosszeg', '>=', $request->evestamosszegtol);
        }
        if ($request->evestamosszegig) {
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return null;
    }

    public function count(Request $request) {
        $ret = new CountResource($this->makeQuery($request)->count());
        return $ret;
    }
}

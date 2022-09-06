<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Kereseslog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $ip_address
 * @property string $endpoint
 * @property mixed $queryparameter
 * @property string $sqlquery
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereQueryparameter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereSqlquery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $url
 * @property string $sqltime
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereSqltime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog whereUrl($value)
 * @property int|null $per_page
 * @method static \Illuminate\Database\Eloquent\Builder|Kereseslog wherePerPage($value)
 */
class Kereseslog extends Model
{
    use HasFactory;

    /**
     * @param Request $request
     * @param string $endpoint
     * @return Kereseslog
     */
    public static function fromRequest(Request $request, string $endpoint)
    {
        $o = new Kereseslog();
        $o->ip_address = $request->ip();
        $o->url = $request->fullUrl();
        $o->endpoint = $endpoint;
        $par = $request->all();
        if (array_key_exists('per_page', $par)) {
            $o->per_page = $par['per_page'];
            unset($par['per_page']);
        }
        unset($par['page']);
        $o->queryparameter = json_encode($par);
        $o->save();
        return $o;
    }

    public function fillSqlAndSave($querylog)
    {
        $this->sqlquery = $querylog['query'];
        $this->sqltime = $querylog['time'];
        $this->save();
    }
}

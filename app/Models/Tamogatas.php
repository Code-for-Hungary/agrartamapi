<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tamogatas
 *
 * @property int $id
 * @property int $ev
 * @property string $name
 * @property string $irszam
 * @property string $varos
 * @property string $utca
 * @property int $osszeg
 * @property int $evesosszeg
 * @property int $is_firm
 * @property int $is_landbased
 * @property string $gender
 * @property string $point_lat
 * @property string $point_long
 * @property string $cegcsoport_id
 * @property string $tamogatott_id
 * @property int $jogcim_id
 * @property int $alap_id
 * @property int $forras_id
 * @property string $megye_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Alap $alap
 * @property-read \App\Models\Cegcsoport $cegcsoport
 * @property-read \App\Models\Forras $forras
 * @property-read \App\Models\Jogcim $jogcim
 * @property-read \App\Models\Megye $megye
 * @property-read \App\Models\Tamogatott $tamogatott
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereAlapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereCegcsoportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereEv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereEvesosszeg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereForrasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereIrszam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereIsFirm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereIsLandbased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereJogcimId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereMegyeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereOsszeg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas wherePointLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas wherePointLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereTamogatottId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereUtca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereVaros($value)
 * @mixin \Eloquent
 * @property int $forrasadat_id
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatas whereForrasadatId($value)
 */
class Tamogatas extends Model
{
    use HasFactory;
    protected $fillable = ['ev', 'name', 'irszam', 'varos', 'utca', 'osszeg', 'evesosszeg',
        'is_firm', 'is_landbased', 'gender', 'point_lat', 'point_long'];

    public function cegcsoport() {
        return $this->belongsTo(Cegcsoport::class, 'cegcsoport_id');
    }

    public function tamogatott() {
        return $this->belongsTo(Tamogatott::class, 'tamogatott_id');
    }

    public function jogcim() {
        return $this->belongsTo(Jogcim::class, 'jogcim_id');
    }

    public function alap() {
        return $this->belongsTo(Alap::class, 'alap_id');
    }

    public function forras() {
        return $this->belongsTo(Forras::class, 'forras_id');
    }

    public function megye() {
        return $this->belongsTo(Megye::class, 'megye_id');
    }
}

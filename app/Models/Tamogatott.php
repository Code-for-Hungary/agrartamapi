<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tamogatott
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $irszam
 * @property string|null $varos
 * @property string|null $utca
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tamogatas[] $tamogatas
 * @property-read int|null $tamogatas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereIrszam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereUtca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereVaros($value)
 * @property string $kurl
 * @method static \Illuminate\Database\Eloquent\Builder|Tamogatott whereKurl($value)
 */
class Tamogatott extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $hidden = ['created_at', 'updated_at'];

    public function tamogatas()
    {
        return $this->hasMany(Tamogatas::class);
    }
}

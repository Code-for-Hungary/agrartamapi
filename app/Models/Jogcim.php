<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Jogcim
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tamogatas[] $tamogatas
 * @property-read int|null $tamogatas_count
 * @property int|null $sorrend
 * @method static \Illuminate\Database\Eloquent\Builder|Jogcim whereSorrend($value)
 */
class Jogcim extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function tamogatas()
    {
        return $this->hasMany(Tamogatas::class);
    }
}

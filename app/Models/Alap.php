<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Alap
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Alap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alap query()
 * @method static \Illuminate\Database\Eloquent\Builder|Alap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alap whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alap whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tamogatas[] $tamogatas
 * @property-read int|null $tamogatas_count
 */
class Alap extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function tamogatas() {
        return $this->hasMany(Tamogatas::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Forras
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Forras newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Forras newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Forras query()
 * @method static \Illuminate\Database\Eloquent\Builder|Forras whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forras whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forras whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forras whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tamogatas[] $tamogatas
 * @property-read int|null $tamogatas_count
 */
class Forras extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function tamogatas() {
        return $this->hasMany(Tamogatas::class);
    }
}

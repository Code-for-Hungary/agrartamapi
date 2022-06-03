<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cegcsoport
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cegcsoport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tamogatas[] $tamogatas
 * @property-read int|null $tamogatas_count
 */
class Cegcsoport extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $hidden = ['created_at', 'updated_at'];

    public function tamogatas() {
        return $this->hasMany(Tamogatas::class);
    }
}

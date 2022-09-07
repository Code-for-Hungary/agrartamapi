<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Megye
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Megye newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Megye newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Megye query()
 * @method static \Illuminate\Database\Eloquent\Builder|Megye whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Megye whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Megye whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Megye whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tamogatas[] $tamogatas
 * @property-read int|null $tamogatas_count
 */
class Megye extends Model
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

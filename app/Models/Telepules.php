<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Telepules
 *
 * @property int $id
 * @property string $name
 * @property string $megye_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $irszam
 * @property-read \App\Models\Megye $megye
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules query()
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules whereIrszam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules whereMegyeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telepules whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Telepules extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function megye() {
        return $this->belongsTo(Megye::class, 'megye_id');
    }

}

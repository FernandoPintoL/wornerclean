<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string|null $ci
 * @property string|null $nombre
 * @property string|null $telefono
 * @property string|null $direccion
 * @property string|null $tipo_cliente
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereCi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereTipoCliente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereUserId($value)
 * @mixin \Eloquent
 */
class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ci',
        'nombre',
        'telefono',
        'direccion',
        'tipo_cliente',
    ];

}

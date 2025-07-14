<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string|null $estado
 * @property string|null $descripcion
 * @property string|null $fecha_solucion
 * @property int $contrato_id
 * @property int $incidencia_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Contrato|null $contrato
 * @property-read \App\Models\Incidencias|null $incidencia
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereContratoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereFechaSolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereIncidenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContratoIncidencias whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContratoIncidencias extends Model
{
    protected $table = 'contrato_incidencias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'contrato_id',
        'incidencia_id',
        'estado',
        'descripcion',
        'fecha_solucion'
    ];

    /**
     * Get the contrato that owns the incidencia.
     */
    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    /**
     * Get the incidencia type that owns the contrato incidencia.
     */
    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(Incidencias::class);
    }
}

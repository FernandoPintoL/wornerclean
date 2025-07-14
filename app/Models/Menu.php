<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $title
 * @property string $href
 * @property string|null $icon
 * @property bool $isMain
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Menu> $submenus
 * @property-read int|null $submenus_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereHref($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    protected $table = "menus";
    protected $primaryKey = "id";
    protected $fillable = [
        'title',
        'href',
        'icon',
        'isMain',
        'parent_id',
        'isSubmenu'
    ];
    public function submenus()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}

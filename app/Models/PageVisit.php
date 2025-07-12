<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = ['count'];

    /**
     * Increment the visit count
     *
     * @return int The new count
     */
    public static function incrementCount(): int
    {
        $visit = self::firstOrCreate(['id' => 1], ['count' => 0]);
        $visit->increment('count');
        return $visit->count;
    }

    /**
     * Get the current visit count
     *
     * @return int The current count
     */
    public static function getCount(): int
    {
        $visit = self::firstOrCreate(['id' => 1], ['count' => 0]);
        return $visit->count;
    }
}

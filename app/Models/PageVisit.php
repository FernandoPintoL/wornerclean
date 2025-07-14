<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = ['count', 'module'];

    /**
     * Increment the visit count for a specific module
     *
     * @param string $module The module name
     * @return int The new count
     */
    public static function incrementCount(string $module = 'global'): int
    {
        $visit = self::firstOrCreate(['module' => $module], ['count' => 0]);
        $visit->increment('count');
        return $visit->count;
    }

    /**
     * Get the current visit count for a specific module
     *
     * @param string $module The module name
     * @return int The current count
     */
    public static function getCount(string $module = 'global'): int
    {
        $visit = self::firstOrCreate(['module' => $module], ['count' => 0]);
        return $visit->count;
    }

    /**
     * Get all module visit counts
     *
     * @return array Array of module => count pairs
     */
    public static function getAllCounts(): array
    {
        $visits = self::all();
        $counts = [];

        foreach ($visits as $visit) {
            $counts[$visit->module] = $visit->count;
        }

        return $counts;
    }
}

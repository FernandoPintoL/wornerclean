<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;

class PageVisitController extends Controller
{
    /**
     * Increment the visit count for a specific module
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function increment(Request $request)
    {
        $module = $request->input('module', 'global');
        $count = PageVisit::incrementCount($module);
        return response()->json(['module' => $module, 'count' => $count]);
    }

    /**
     * Get the current visit count for a specific module
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount(Request $request)
    {
        $module = $request->input('module', 'global');
        $count = PageVisit::getCount($module);
        return response()->json(['module' => $module, 'count' => $count]);
    }

    /**
     * Get all module visit counts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCounts()
    {
        $counts = PageVisit::getAllCounts();
        return response()->json(['counts' => $counts]);
    }
}

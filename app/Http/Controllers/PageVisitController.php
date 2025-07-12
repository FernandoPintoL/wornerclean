<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;

class PageVisitController extends Controller
{
    /**
     * Increment the visit count
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function increment()
    {
        $count = PageVisit::incrementCount();
        return response()->json(['count' => $count]);
    }

    /**
     * Get the current visit count
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        $count = PageVisit::getCount();
        return response()->json(['count' => $count]);
    }
}

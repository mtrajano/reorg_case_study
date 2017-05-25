<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function getSearch(Request $request)
    {
        $first_name = $request->input('first_name');

        $results = \DB::table('physician_payments')
            ->where('Physician_First_Name', $first_name)
            ->get();

        return response()->json($results);
    }
}

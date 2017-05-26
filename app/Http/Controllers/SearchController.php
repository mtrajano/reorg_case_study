<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $firstName = $request->input('first_name');

        $results = \DB::table('physician_payments')
            ->where('Physician_First_Name', $firstName)
            ->get();

        return response()->json($results);
    }

    public function download(Request $request)
    {
        $idArray = $request->input('ids');

        $results = \DB::table('physician_payments')
            ->whereIn('Id', $idArray)
            ->get();

        $tempFileName = "/tmp/" . uniqid('reorg');
        $fp = fopen($tempFileName, 'w');

        $header = [
            'Physician_First_Name',
            'Physician_Last_Name',
            'Physician_Specialty',
            'Physician_License_State_code1',
            'Date_of_Payment',
            'Total_Amount_of_Payment_USDollars'
        ];

        fputcsv($fp, $header);

        foreach($results as $result) {
            $row = [
                $result->Physician_First_Name,
                $result->Physician_Last_Name,
                $result->Physician_Specialty,
                $result->Physician_License_State_code1,
                $result->Date_of_Payment,
                $result->Total_Amount_of_Payment_USDollars,
            ];

            fputcsv($fp, $row);
        }

        fclose($fp);

        $fileName = date('YmdHis') . '.csv';

        $responseHeader = ['Content-Type' => 'application/vnd.ms-excel; charset=utf-8', 'Content-Disposition' => 'attachment'];
        return response()->download($tempFileName, $fileName, $responseHeader)->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mtrajano\SimpleExcel\Workbook;

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

        $workbook = new Workbook();
        $worksheet = $workbook->getActiveSheet();

        $row = 1;
        $header = ['Physician_First_Name', 'Physician_Last_Name', 'Physician_Specialty', 'Physician_License_State_code1', 'Date_of_Payment', 'Total_Amount_of_Payment_USDollars'];

        foreach ($header as $column) {
            $worksheet[$row][] = $column;
        }
        $row++;

        foreach ($results as $result) {
            $worksheet[$row][] = $result->Physician_First_Name;
            $worksheet[$row][] = $result->Physician_Last_Name;
            $worksheet[$row][] = $result->Physician_Specialty;
            $worksheet[$row][] = $result->Physician_License_State_code1;
            $worksheet[$row][] = $result->Date_of_Payment;
            $worksheet[$row][] = $result->Total_Amount_of_Payment_USDollars;
            $row++;
        }

        $fileName = date('YmdHis') . '.xlsx';
        $workbook->save($fileName, Workbook::WRITE_Excel2007);

        $responseHeader = ['Content-Type' => 'application/vnd.ms-excel; charset=utf-8', 'Content-Disposition' => 'attachment'];
        return response()->download($fileName, $fileName, $responseHeader)->deleteFileAfterSend(true);
    }
}

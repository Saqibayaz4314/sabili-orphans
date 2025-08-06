<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrphansExcelImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;


class UplodeOrphanController extends Controller
{
    public function uplodeExcel(Request $request)
    {


        $request->validate([
            'orphan_excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new OrphansExcelImport, $request->file('orphan_excel_file'));

            return back()->with('success', 'تم رفع واستيراد ملف Excel بنجاح.');
        } catch (\Exception $e) {
          
            return back()->with('danger', 'حدث خطأ أثناء استيراد ملف Excel.');
        }

    }

    public function uplodeAccess(Request $request){

        $request->validate([
            'orphan_access_file' => 'required|file|mimes:accdb',
        ]);

        $path = $request->file('orphan_access_file')->storeAs('temp', 'orphans.accdb');
        $fullPath = storage_path("app/{$path}");

        try {
            $dsn = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$fullPath;";
            $conn = new PDO($dsn);

            $stmt = $conn->query("SELECT * FROM Orphans"); // غيّر اسم الجدول حسب الموجود في Access

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                DB::table('orphans')->insert($row); // غيّر حسب هيكل جدولك
            }

            return back()->with('success', '✅ تم استيراد ملف Access بنجاح.');
        } catch (\Exception $e) {
            // Log::error('Access Upload Error: ' . $e->getMessage());
            return back()->with('error', '❌ فشل في قراءة ملف Access. تأكد من تنسيقه ومسار الاتصال.');
        }

    }
}

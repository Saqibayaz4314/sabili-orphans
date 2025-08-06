<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportOrphansPdfImport;
use niklasravnsborg\LaravelPdf\Facades\Pdf;


class DownloadController extends Controller
{

    public function downloadPdf(Request $request)
    {

        $ids = explode(',', $request->input('ids'));
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
        ]);


        $orphans = Orphan::whereIn('id', $validated['ids'])->get();

        // تمرير كل الأيتام إلى نفس الـ View
        $pdf = Pdf::loadView('pages.pdf.index', [
            'orphans' => $orphans,
            'fields' => $request->fields, // تمرير الحقول المطلوبة
        ]);

        // إعداد اسم الملف
        // $orphanNames = $orphans->pluck('name')->implode('_');
        // $filename = $orphans . 'orphans_' . now()->format('Ymd') . '.pdf';

        $filename = 'orphans_' . now()->format('Ymd') . '.pdf';


        // تصدير الملف مباشرة
        return $pdf->download($filename);
        // return response($pdf->output(), 200)
        // ->header('Content-Type', 'application/pdf')
        // ->header('Content-Disposition', 'inline; filename="' . $filename . '"');



    }

    public function downloadExcel(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
        ]);

        return Excel::download(new ExportOrphansPdfImport($validated['ids'] , $request->fields), 'orphans.xlsx');
    }

    // public function downloadAccess(Request $request)
    // {
    //     $ids = explode(',', $request->input('ids'));
    //     $request->merge(['ids' => $ids]);

    //     $validated = $request->validate([
    //         'ids' => 'required|array',
    //         'ids.*' => 'integer|exists:orphans,id',
    //     ]);
    //     dd($validated);
    //     // $ids = $request->input('ids');
    // }
}

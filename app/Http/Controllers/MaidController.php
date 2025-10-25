<?php

namespace App\Http\Controllers;

use App\Models\Maid;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class MaidController extends Controller
{
    public function exportPdf(): Response
    {
        $maids = Maid::orderBy('last_name')->orderBy('first_name')->get();

        $pdf = Pdf::loadView('maids.pdf', [
            'maids' => $maids,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('maids-list.pdf');
    }
}

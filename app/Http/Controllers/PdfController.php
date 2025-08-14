<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfController
{
    /**
     * Gera um PDF a partir de uma view
     */
    public function generateFromView(Request $request, $viewName)
    {
        try {
            $data = $request->all();
            
            if (!View::exists("pdfs.{$viewName}")) {
                abort(404, "Template PDF '{$viewName}' não encontrado.");
            }
            
            $pdf = Pdf::loadView("pdfs.{$viewName}", $data);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false
            ]);
            
            $filename = "relatorio_promotores_" . date('Y-m-d_H-i-s') . ".pdf";
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Erro ao gerar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

   
    public function generateFromHtml(Request $request)
    {
        $html = $request->input('html', '<h1>PDF Gerado</h1>');
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('documento.pdf');
    }

    public function streamPdf(Request $request, $viewName)
    {
        $data = $request->all();
        
        $pdf = Pdf::loadView("pdfs.{$viewName}", $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream("{$viewName}.pdf");
    }

   
    public function generateReport(Request $request, $reportType)
    {
        $data = $request->all();
        
        $pdf = Pdf::loadView("pdfs.reports.{$reportType}", $data);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        
        return $pdf->download("relatorio_{$reportType}.pdf");
    }

   
    public function savePdf(Request $request, $viewName)
    {
        $data = $request->all();
        $filename = $request->input('filename', $viewName);
        
        $pdf = Pdf::loadView("pdfs.{$viewName}", $data);
        $pdf->setPaper('a4', 'portrait');
        
        $path = storage_path("app/public/pdfs/{$filename}.pdf");
        $pdf->save($path);
        
        return response()->json([
            'success' => true,
            'message' => 'PDF salvo com sucesso',
            'path' => $path
        ]);
    }
} 
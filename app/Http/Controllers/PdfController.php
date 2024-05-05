<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use setasign\Fpdi\Fpdi;

class PdfController extends Controller
{
   
    public function generatePDF()
{
    set_time_limit(1000000);

    // Load the HTML content for the front page
    $frontPageHtml = view('pdf.front_page')->render();

    



    // Load the HTML content for the content pages (repeat 10 times)
    $contentHtml = '';
    for ($i = 0; $i < 10; $i++) {
        $contentHtml .= view('pdf.content_page')->render();
    }

    // Load the HTML content for the back page
    $backPageHtml = view('pdf.back_page')->render();

    // Generate PDF for front page with specific size
    $frontPdfPath = tempnam(sys_get_temp_dir(), 'front');
    PDF::loadHtml($frontPageHtml)->setPaper([10, 10, 600, 860])->save($frontPdfPath); // Adjusted dimensions here

    // Generate PDF for content pages with different size
    $contentPdfPath = tempnam(sys_get_temp_dir(), 'content');
    PDF::loadHtml($contentHtml)->setPaper([10, 10, 600, 860])->save($contentPdfPath); // Adjusted dimensions here

    // Generate PDF for back page with specific size
    $backPdfPath = tempnam(sys_get_temp_dir(), 'back');
    PDF::loadHtml($backPageHtml)->setPaper([10, 10, 600, 860])->save($backPdfPath); // Adjusted dimensions here

    // Merge PDFs
    $pdf = new Fpdi();

    // Add front page
    $frontPages = $pdf->setSourceFile($frontPdfPath);
    for ($i = 1; $i <= $frontPages; $i++) {
        $pdf->AddPage();
        $tplIdx = $pdf->importPage($i);
        $pdf->useTemplate($tplIdx);
    }

    // Add content pages
    $contentPages = $pdf->setSourceFile($contentPdfPath);
    for ($i = 1; $i <= $contentPages; $i++) {
        $pdf->AddPage();
        $tplIdx = $pdf->importPage($i);
        $pdf->useTemplate($tplIdx);
    }

    // Add back page
    $backPages = $pdf->setSourceFile($backPdfPath);
    for ($i = 1; $i <= $backPages; $i++) {
        $pdf->AddPage();
        $tplIdx = $pdf->importPage($i);
        $pdf->useTemplate($tplIdx);
    }

    $pdf->output('sample.pdf', 'D'); // Download the merged PDF

    // Clean up temporary files
    unlink($frontPdfPath);
    unlink($contentPdfPath);
    unlink($backPdfPath);
}


}

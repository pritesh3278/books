<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

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

        

        // Concatenate all HTML content
        $pdfHtml = $frontPageHtml . $contentHtml . $backPageHtml;


        // Generate the PDF
        $pdf = PDF::loadHtml($pdfHtml)->setPaper([0, 0, 522, 540], 'landscape');
        return $pdf->download('sample.pdf');
    }
}




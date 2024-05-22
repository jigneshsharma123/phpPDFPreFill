<?php

namespace App\Controllers;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;

class PdfController extends BaseController
{
    public function generateW9()
    {
        $providerData = [
            'name' => 'John Doe',
            'phone' => '2422342534',
            'id' => rand(1, 100),
        ];
        $w9TemplatePath = WRITEPATH . 'uploads/new.pdf';
        $outputPath = $this->preFillW9($providerData, $w9TemplatePath);
        echo 'Download your pre-filled W-9 form: <a href="' . base_url('uploads/prefilled_w9_' . $providerData['id'] . '.pdf') . '">Download</a>';
    }

    private function preFillW9($providerData, $w9TemplatePath)
    {
        try {
            $pdf = new Fpdi();

            $pdf->setSourceFile($w9TemplatePath);
            $pageId = $pdf->importPage(1);

            $pdf->AddPage();
            $pdf->useImportedPage($pageId, 10, 10, 200);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetXY(110, 55);
            $pdf->Cell(50, 10, $providerData['name']);

            $pdf->SetXY(110, 85);
            $pdf->Cell(50, 10, $providerData['phone']);

            $outputPath = WRITEPATH . 'uploads/prefilled_w9_' . $providerData['id'] . '.pdf';
            $pdf->Output($outputPath, 'F');

            return $outputPath;
        } catch (CrossReferenceException $e) {
            // Handle the exception
            echo "Error: " . $e->getMessage();
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
            return null;
        }
    }
}

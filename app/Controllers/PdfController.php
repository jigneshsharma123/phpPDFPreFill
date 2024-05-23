<?php

namespace App\Controllers;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;

class PdfController extends BaseController
{
    public function generateW9()
    {
        $providerData = [
            'name' => 'jignesh sharma',
            'business_name' => 'exampleBusiness',
            'account' => 'account_details',
            'phone' => '2422342534',
            'num' => '1',
            'id' => rand(1, 100),
        ];
        $w9TemplatePath = WRITEPATH . 'uploads/FormW9forSSNlocal.pdf';
        $outputPath = $this->preFillW9($providerData, $w9TemplatePath);
        echo 'Download your pre-filled W-9 form: <a href="' . base_url('uploads/prefilled_w9_' . $providerData['id'] . '.pdf') . '">Download</a>';
    }

    private function preFillW9($providerData, $w9TemplatePath)
    {
        try {
            $pdf = new Fpdi();

            // Get the number of pages in the source PDF
            $pageCount = $pdf->setSourceFile($w9TemplatePath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pageId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useImportedPage($pageId, 10, 10, 200);

                if ($pageNo == 1) {
                    // Set font and color for the text
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetTextColor(0, 0, 0);

                    // Fill in the provider data on the first page
                    $pdf->SetXY(30, 36);
                    $pdf->Cell(100, 40, $providerData['name']);
                    // business name
                    $pdf->SetXY(30, 45);
                    $pdf->Cell(100, 40, $providerData['business_name']);
                    // address
                    $pdf->SetXY(31, 90);
                    $pdf->Cell(100, 44, $providerData['business_name']);

                    // city, state
                    $pdf->SetXY(31, 97);
                    $pdf->Cell(150, 44, $providerData['business_name']);
                    // list account number
                    $pdf->SetXY(31, 105);
                    $pdf->Cell(150, 44, $providerData['business_name']);

                    // first number box
                    $pdf->SetXY(146, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(151, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(156, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(166, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(170, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(180, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(185, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(190, 130);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(195, 130);
                    $pdf->Cell(5, 20, $providerData['num']);

                    // second number box 
                    $pdf->SetXY(146, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    // 2
                    $pdf->SetXY(151, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    // 3
                    $pdf->SetXY(161, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    // 4
                    $pdf->SetXY(165, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(170, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(175, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(180, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(185, 145);
                    $pdf->Cell(5, 20, $providerData['num']);
                    $pdf->SetXY(190, 145);
                    $pdf->Cell(5, 20, $providerData['num']);

                    // check marks 
                    $color = [0, 0, 1];
                    $size = 1;
                    $thickness = 0.2;
                    $x = 34.5;
                    $y = 75.5;
                    // drawing the tick mark
                    $pdf->SetLineWidth($thickness);
                    $pdf->SetDrawColor($color[0], $color[1], $color[2]);
                    $pdf->Line($x, $y, $x + $size, $y + $size);
                    $pdf->Line($x + $size, $y + $size, $x + 2 * $size, $y - $size);
                }

                // Apply any modifications to other pages if necessary
            }

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

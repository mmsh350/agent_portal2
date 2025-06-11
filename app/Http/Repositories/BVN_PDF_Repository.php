<?php

namespace App\Http\Repositories;

use App\Models\Verification;
use TCPDF;

class BVN_PDF_Repository
{


    public function plasticPDF($bvn_no)
    {
        // Check if record exists and retrieve the latest record
        if (Verification::where('idno', $bvn_no)->exists()) {
            $verifiedRecord = Verification::where('idno', $bvn_no)
                ->latest()
                ->first();

            // Prepare data for the PDF
            $bvnData = [
                "bvn" => $verifiedRecord->idno,
                "fName" => $verifiedRecord->first_name,
                "sName" => $verifiedRecord->last_name,
                "mName" => $verifiedRecord->middle_name,
                "tId" => $verifiedRecord->trackingId,
                "address" => $verifiedRecord->address,
                "lga" => $verifiedRecord->lga,
                "state" => $verifiedRecord->state,
                "gender" => ($verifiedRecord->gender === 'Male') ? "M" : "F",
                "dob" => $verifiedRecord->dob,
                "photo" => str_replace('data:image/jpg;base64,', '', $verifiedRecord->photo)
            ];


            $names = html_entity_decode($verifiedRecord->first_name) . ' ' . html_entity_decode($verifiedRecord->last_name);

            // Initialize TCPDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
            $pdf->setPrintHeader(false);
            $pdf->SetCreator('Abu');
            $pdf->SetAuthor('Zulaiha');
            $pdf->SetTitle($names);
            $pdf->SetSubject('Plastic');
            $pdf->SetKeywords('plastic, TCPDF, PHP');
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            $pdf->SetFont('dejavuserifcondensedbi', '', 12);

            // // Add text
            // $txt = "Please find below your new High Resolution NIN Slip...";
            // $pdf->MultiCell(150, 20, $txt, 0, 'C', false, 1, 35, 20, true, 0, false, true, 0, 'T', false);

            // Use JPG images instead of PNG
            $pdf->Image('assets/card_and_Slip/bvn.jpg', 69.5, 48, 78, 50, 'JPG', '', '', false, 300, '', false, false, 0);
            $pdf->Image('assets/card_and_Slip/finger.jpg', 69.3, 101, 78, 50, 'JPG', '', '', false, 300, '', false, false, 1);

            // Add barcode
            $style = [
                'border' => false,
                'padding' => 0,
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255]
            ];
            // $datas = '{BVN: ' . $bvnData['bvn'] . ', NAME: ' . html_entity_decode($bvnData['fName']) . ' ' . html_entity_decode($bvnData['mName']) . ' ' . html_entity_decode($bvnData['sName']) . ', DOB: ' . $bvnData['dob'] . ', Status:Verified}';
            // $pdf->write2DBarcode($datas, 'QRCODE,H', 128, 53, 20, 20, $style, 'H');

            // Add image from base64
            $photo = $bvnData['photo'];
            $imgdata = base64_decode($photo);
            $pdf->Image('@' . $imgdata, 73.5, 65.7, 17.8, 22, 'JPG', '', '', false, 300, '', false, false, 0);

            // Add text
            $sur = html_entity_decode($bvnData['sName']);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(93.3, 66.5, strtoupper($sur));

            $othername = html_entity_decode($bvnData['fName']) . ', ' . html_entity_decode($bvnData['mName']);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(93.3, 73.5, strtoupper($othername));

            $dob = $bvnData['dob'];
            $newD = strtotime($dob);
            $cdate = date("d M Y", $newD);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(93.3, 81.2, $cdate);

            $gender = $bvnData['gender'];
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(114, 81, $gender);

            $issueD = date("d M Y");
            $pdf->SetFont('helvetica', '', 6);
            $pdf->Text(129.5, 79, $issueD);

            // Format BVN
            $bvn = $bvnData['bvn'];
            $pdf->setTextColor(0, 0, 0);
            $newBVN = substr($bvn, 0, 4) . " " . substr($bvn, 4, 3) . " " . substr($bvn, 7);
            $pdf->SetFont('helvetica', '', 15);
            $pdf->Text(91, 90, $newBVN);

            // Save and download PDF

            $filename =  'Plastic BVN ID - ' . $bvn_no . '.pdf';
            $pdfContent = $pdf->Output($filename, 'S');

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($pdfContent));
        } else {
            return response()->json([
                "message" => "Error",
                "errors" => ["Not Found" => "Verification record not found!"]
            ], 422);
        }
    }
}

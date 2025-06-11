<?php

namespace App\Http\Repositories;

use App\Models\Verification;
use TCPDF;

class NIN_PDF_Repository
{
    public function regularPDF($nin_no)
    {

        if (Verification::where('idno', $nin_no)->exists()) {
            $verifiedRecord = Verification::where('idno', $nin_no)
                ->latest()
                ->first();

            $ninData = [
                "nin" => $verifiedRecord->idno,
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

            $names = $verifiedRecord->first_name . ' ' . $verifiedRecord->last_name;
            // Initialize TCPDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
            $pdf->setPrintHeader(false);

            // Set document information
            $pdf->SetCreator('Abu');
            $pdf->SetAuthor('Zulaiha');
            $pdf->SetTitle(html_entity_decode($names));
            $pdf->SetSubject('Regular');
            $pdf->SetKeywords('Regular, TCPDF, PHP');
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // Add a new page
            $pdf->AddPage();

            // Load the background image
            $pdf->Image('assets/card_and_Slip/regular.png', 15, 50, 178, 80, '', '', '', false, 300, '', false, false, 0);

            // Decode and add the photo
            $photo = $ninData['photo'];
            $imgdata = base64_decode($photo);
            $pdf->Image('@' . $imgdata, 166.8, 69.3, 25, 31, '', '', '', false, 300, '', false, false, 0);

            // Add text fields using 'helvetica' font
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(85, 71, html_entity_decode($ninData['sName']));
            $pdf->Text(85, 79.7, html_entity_decode($ninData['fName']));
            $pdf->Text(85, 86.8, html_entity_decode($ninData['mName']));

            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(85, 96, $ninData['gender']);

            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(32, 71.8, $ninData['tId']);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(25, 79.5, $ninData['nin']);

            $pdf->SetFont('helvetica', '', 9);
            $pdf->MultiCell(50, 20, html_entity_decode($ninData['address']), 0, 'L', false, 1, 116, 74, true);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(116, 93, $ninData['lga']);
            $pdf->Text(116, 97, $ninData['state']);

            // Output the PDF
            $filename =  'Regular NIN Slip - ' . $nin_no . '.pdf';
            $pdfContent = $pdf->Output($filename, 'S');

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename=' . $filename)
                ->header('Content-Length', strlen($pdfContent));
        } else {

            return response()->json([
                "message" => "Error",
                "errors" => array("Not Found" => "Verification record not found !")
            ], 422);
        }
    }

    public function standardPDF($nin_no)
    {

        if (Verification::where('idno', $nin_no)->exists()) {
            $verifiedRecord = Verification::where('idno', $nin_no)
                ->latest()
                ->first();

            $ninData = [
                "nin" => $verifiedRecord->idno,
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

            $names = $verifiedRecord->first_name . ' ' . $verifiedRecord->last_name;

            // Generate PDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

            // Set document information
            $pdf->setPrintHeader(false);
            $pdf->SetCreator('Abu');
            $pdf->SetAuthor('Zulaiha');
            $pdf->SetTitle(html_entity_decode($names));
            $pdf->SetSubject('Standard');
            $pdf->SetKeywords('Standard, TCPDF, PHP');
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            $pdf->SetFont('dejavuserifcondensedbi', '', 12);
            $txt = "Please find below your new High Resolution NIN Slip. You may cut it out of the paper, fold and laminate as desired. Please DO NOT allow others to make copies of your NIN Slip.\n";
            $pdf->MultiCell(150, 20, $txt, 0, 'C', false, 1, 35, 20, true, 0, false, true, 0, 'T', false);

            // Add images (using JPG instead of PNG)
            $pdf->Image('assets/card_and_Slip/standard.jpg', 70, 50, 80, 50, '', '', '', false, 300, '', false, false, 0);
            $pdf->Image('assets/card_and_Slip/back.jpg', 70, 101, 80, 50, '', '', '', false, 300, '', false, false, 0);

            // Add QR code
            $style = [
                'border' => false,
                'padding' => 0,
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255]
            ];
            $datas = '{NIN: ' . $ninData['nin'] . ', NAME:' . html_entity_decode($ninData['fName']) . ' ' . html_entity_decode($ninData['mName']) . ' ' . html_entity_decode($ninData['sName']) . ', DOB: ' . $ninData['dob'] . ', Status:Verified}';
            $pdf->write2DBarcode($datas, 'QRCODE,H', 131.2, 64.7, 14.2, 13.5, $style, 'H');
            $pdf->Image('assets/card_and_Slip/pin.jpg', 135.8, 69.5, 4.5, 4.5, '', '', '', false, 300, '', false, false, 0);

            // Decode the base64 image
            $photo = base64_decode($ninData['photo']);
            $pdf->Image('@' . $photo, 72, 62, 18, 23, '', '', '', false, 300, '', false, false, 0);

            // Add text fields
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(91.5, 65, html_entity_decode($ninData['sName']));
            $pdf->Text(91.5, 72, html_entity_decode($ninData['fName']) . ', ' . html_entity_decode($ninData['mName']));
            $newD = strtotime($ninData['dob']);
            $cdate = date("d M Y", $newD);
            $pdf->Text(91.5, 78.7, $cdate);

            $issueD = date("d M Y");
            $pdf->Text(128, 80, $issueD);

            // Add NIN
            $nin = $ninData['nin'];
            $newNin = substr($nin, 0, 4) . " " . substr($nin, 4, 3) . " " . substr($nin, 7);
            $pdf->SetFont('helvetica', '', 21);
            $pdf->Text(81, 89, $newNin);

            // Add watermark
            $pdf->StartTransform();
            $pdf->Rotate(50, 88, 95);
            $pdf->setTextColor(220, 220, 220);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(80, 80, $nin);
            $pdf->StopTransform();

            // Output PDF
            $filename =  'Standard NIN Slip - ' . $nin_no . '.pdf';
            $pdfContent = $pdf->Output($filename, 'S');

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename=' . $filename);
        } else {
            return response()->json([
                "message" => "Error",
                "errors" => ["Not Found" => "Verification record not found!"]
            ], 422);
        }
    }

    public function premiumPDF($nin_no)
    {
        // Check if record exists and retrieve the latest record
        if (Verification::where('idno', $nin_no)->exists()) {
            $verifiedRecord = Verification::where('idno', $nin_no)
                ->latest()
                ->first();

            // Prepare data for the PDF
            $ninData = [
                "nin" => $verifiedRecord->idno,
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
            $pdf->SetSubject('Premium');
            $pdf->SetKeywords('premium, TCPDF, PHP');
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            $pdf->SetFont('dejavuserifcondensedbi', '', 12);

            // Add text
            $txt = "Please find below your new High Resolution NIN Slip...";
            $pdf->MultiCell(150, 20, $txt, 0, 'C', false, 1, 35, 20, true, 0, false, true, 0, 'T', false);

            // Use JPG images instead of PNG
            $pdf->Image('assets/card_and_Slip/premium.jpg', 70, 50, 80, 50, 'JPG', '', '', false, 300, '', false, false, 0);
            $pdf->Image('assets/card_and_Slip/back.jpg', 70, 101, 80, 50, 'JPG', '', '', false, 300, '', false, false, 0);

            // Add barcode
            $style = [
                'border' => false,
                'padding' => 0,
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255]
            ];
            $datas = '{NIN: ' . $ninData['nin'] . ', NAME: ' . html_entity_decode($ninData['fName']) . ' ' . html_entity_decode($ninData['mName']) . ' ' . html_entity_decode($ninData['sName']) . ', DOB: ' . $ninData['dob'] . ', Status:Verified}';
            $pdf->write2DBarcode($datas, 'QRCODE,H', 128, 53, 20, 20, $style, 'H');

            // Add image from base64
            $photo = $ninData['photo'];
            $imgdata = base64_decode($photo);
            $pdf->Image('@' . $imgdata, 71.5, 62, 20, 25, 'JPG', '', '', false, 300, '', false, false, 0);

            // Add text
            $sur = html_entity_decode($ninData['sName']);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(93.3, 66.5, $sur);

            $othername = html_entity_decode($ninData['fName']) . ', ' . html_entity_decode($ninData['mName']);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(93.3, 73.5, $othername);

            $dob = $ninData['dob'];
            $newD = strtotime($dob);
            $cdate = date("d M Y", $newD);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(93.3, 80.5, $cdate);

            $gender = $ninData['gender'];
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Text(114, 80.5, $gender);

            $issueD = date("d M Y");
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Text(128, 81.8, $issueD);

            // Format NIN
            $nin = $ninData['nin'];
            $pdf->setTextColor(0, 0, 0);
            $newNin = substr($nin, 0, 4) . " " . substr($nin, 4, 3) . " " . substr($nin, 7);
            $pdf->SetFont('helvetica', '', 21);
            $pdf->Text(81, 91, $newNin);

            // Watermark
            $pdf->StartTransform();
            $pdf->Rotate(50, 88, 95);
            $pdf->setTextColor(165, 162, 156);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(80, 80, $nin);
            $pdf->StopTransform();

            $pdf->StartTransform();
            $pdf->Rotate(50, 90, 95);
            $pdf->setTextColor(165, 162, 156);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(77, 86, $nin);
            $pdf->StopTransform();

            $pdf->StartTransform();
            $pdf->Rotate(127, 118, 74);
            $pdf->setTextColor(165, 162, 156);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(80, 80, $nin);
            $pdf->StopTransform();

            $pdf->setTextColor(165, 162, 156);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(129, 73, $nin);

            // Save and download PDF

            $filename =  'Premium NIN Slip - ' . $nin_no . '.pdf';
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

    function extractBase64AndType($dataUrl) {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
            $type = strtoupper($matches[1]); // e.g., JPG, PNG
            $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
            return [$type, $data];
        }
        return [null, $dataUrl]; // fallback
    }

    public function basicPDF($nin_no)
    {
        // Check if record exists and retrieve the latest record
        if (Verification::where('idno', $nin_no)->exists()) {
            $verifiedRecord = Verification::where('idno', $nin_no)
                ->latest()
                ->first();

              list($photoType, $photoBase64) = $this->extractBase64AndType($verifiedRecord->photo);
              list($signatureType, $signatureBase64) = $this->extractBase64AndType($verifiedRecord->signature);

            // Prepare data for the PDF
            $ninData = [
                "nin" => $verifiedRecord->idno,
                "fName" => $verifiedRecord->first_name,
                "sName" => $verifiedRecord->last_name,
                "mName" => $verifiedRecord->middle_name,
                "tId" => $verifiedRecord->trackingId,
                "phoneno" =>  str_replace('+234', '0', $verifiedRecord->phoneno),
                "address" => $verifiedRecord->address,
                "lga" => $verifiedRecord->lga,
                "state" => $verifiedRecord->state,
                'town' => $verifiedRecord->town,
                "residence_lga" => $verifiedRecord->residence_lga,
                "residence_state" => $verifiedRecord->residence_state,
                'residence_town' => $verifiedRecord->residence_town,
                "gender" => ($verifiedRecord->gender === 'Male') ? "M" : "F",
                "dob" => $verifiedRecord->dob,
                "photo" => $photoBase64,
                "photo_type" => $photoType,
                "signature" => $signatureBase64,
                "signature_type" => $signatureType,
            ];

            $names = html_entity_decode($verifiedRecord->first_name) . ' ' . html_entity_decode($verifiedRecord->last_name);

            // Initialize TCPDF
            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
            $pdf->setPrintHeader(false);
            $pdf->SetCreator('Abu');
            $pdf->SetAuthor('Zulaiha');
            $pdf->SetTitle($names);
            $pdf->SetSubject('Premium');
            $pdf->SetKeywords('premium, TCPDF, PHP');
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->AddPage();
            $pdf->SetFont('dejavuserifcondensedbi', '', 12);

            // Add text
            // $txt = "Please find below your new High Resolution NIN Slip...";
            // $pdf->MultiCell(150, 20, $txt, 0, 'C', false, 1, 35, 20, true, 0, false, true, 0, 'T', false);

            // Use JPG images instead of PNG
            $pdf->Image('assets/card_and_Slip/basic.jpg', 20, 25, 250, 163, 'JPG', '', '', false, 300, '', false, false, 0);
            // $pdf->Image('assets/card_and_Slip/back.jpg', 70, 101, 80, 50, 'JPG', '', '', false, 300, '', false, false, 0);

            // Add barcode
            $style = [
                'border' => false,
                'padding' => 0,
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255]
            ];
            // $datas = '{NIN: ' . $ninData['nin'] . ', NAME: ' . html_entity_decode($ninData['fName']) . ' ' . html_entity_decode($ninData['mName']) . ' ' . html_entity_decode($ninData['sName']) . ', DOB: ' . $ninData['dob'] . ', Status:Verified}';
            // $pdf->write2DBarcode($datas, 'QRCODE,H', 128, 53, 20, 20, $style, 'H');

            // Add image from base64
            $photo = $ninData['photo'];
            $imgdata = base64_decode($photo);
            $pdf->Image('@' . $imgdata, 92.1, 67.8, 46.5, 49, $ninData['photo_type'], '', '', false, 300, '', false, false, 0);

            $signature = $ninData['signature'];
            $signature = base64_decode($signature);
            $pdf->Image('@' . $signature, 109, 117.5, 30, 8, $ninData['signature_type'], '', '', false, 300, '', false, false, 0);

             // Format NIN
             $nin = $ninData['nin'];
             $pdf->setTextColor(90, 90, 90);
             $newNin = substr($nin, 0, 4) . " " . substr($nin, 4, 3) . " " . substr($nin, 7);
             $pdf->SetFont('helvetica', 'B', 18);
             $pdf->Text(74, 125.3, $newNin);

             $first_name = html_entity_decode($ninData['fName']);
             $pdf->SetFont('helvetica', 'B', 10);
             $pdf->Text(51, 70, $first_name);

            $midle_name = html_entity_decode($ninData['mName']);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 78.5, $midle_name);

            $pdf->setTextColor(90, 90, 90);
            $sur = html_entity_decode($ninData['sName']);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 92.1, $sur);

            $dob = $ninData['dob'];
            $newD = strtotime($dob);
            $cdate = date("d M Y", $newD);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 101, $cdate);

            $gender = $ninData['gender'];
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 116, $gender);

            $tId = $ninData['tId'];
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 138.5, $tId);

            $phoneno = $ninData['phoneno'];
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(117, 138.5, $phoneno);


            $state = $ninData['state'];
            $pdf->setTextColor(90, 90, 90);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 161, $state);


            $lga = $ninData['lga'];
            $pdf->setTextColor(90, 90, 90);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(117, 160.9, $lga);

            // $town = $ninData['town'];
            // $pdf->setTextColor(90, 90, 90);
            // $pdf->SetFont('helvetica', 'B', 10);
            // $pdf->Text(130, 160.9, $town);


            $residence_state = $ninData['residence_state'];
            $pdf->setTextColor(90, 90, 90);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(51, 149.8, $residence_state);


            $residence_lga = $ninData['residence_lga'];
            $pdf->setTextColor(90, 90, 90);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(117, 149.8, $residence_lga);


            $town = $ninData['town'];
            $pdf->setTextColor(90, 90, 90);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(130, 149.8, $town);


            $address = $ninData['address'];
            $pdf->setTextColor(90, 90, 90);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Text(47, 171, $address);

            // Save and download PDF

            $filename =  'Basic NIN Slip - ' . $nin_no . '.pdf';
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

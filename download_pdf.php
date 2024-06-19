<?php
require_once('tcpdf.php');
include 'includes/dbh.inc.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if (!isset($_GET['cons_id'])) {
    header('location:profile.php');
    exit;
}

$cons_id = $_GET['cons_id'];

// Fetch user information
$select = mysqli_query($conn, "SELECT * FROM `users` WHERE usersId = '$user_id'") or die('Query failed');
$fetch = mysqli_fetch_assoc($select);

// Fetch user's consultation
$consultation_query = mysqli_query($conn, "SELECT consultations.*, categories.categoryName AS master_category, masters.mFullName
                                            FROM `consultations`
                                            INNER JOIN `categories_masters` ON consultations.masterId = categories_masters.masterId
                                            INNER JOIN `categories` ON categories_masters.categoryId = categories.categoryId
                                            INNER JOIN `masters` ON consultations.masterId = masters.masterId
                                            WHERE consultations.usersId = '$user_id' AND consultations.cons_id = '$cons_id'") or die('Query failed: ' . mysqli_error($conn));

$consultation = mysqli_fetch_assoc($consultation_query);

// Create new PDF document
$pdf = new TCPDF();
$pdf->AddPage();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jūsu Vietne');
$pdf->SetTitle('Konsultācijas informācija');
$pdf->SetSubject('Konsultācijas detaļas');
$pdf->SetKeywords('TCPDF, PDF, konsultācija, lietotājs');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Informācija par konsultāciju', 'Konsultācijas detaļas');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('dejavusans', '', 12); // Change font to DejaVuSans

// Add content
$html = '<h1 style="text-align: center;">Soins</h1>';
$html .= '<p style="text-align: center;">Ģertrūdes iela 2a</p>';
$html .= '<p><strong>Vārds:</strong> ' . $fetch['name'] . '</p>';
$html .= '<p><strong>E-pasts:</strong> ' . $fetch['email'] . '</p>';
$html .= '<p><strong>Telefona numurs:</strong> ' . $fetch['number'] . '</p>';

$html .= '<h2 style="text-align: center;">Informācija par konsultāciju</h2>';

$html .= '<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #eee;">
                    <th style="text-align: center; width: 15%;">Datums</th>
                    <th style="text-align: center; width: 15%;">Laiks</th>
                    <th style="text-align: center; width: 15%;">Tips</th>
                    <th style="text-align: center; width: 25%;">Kategorija</th>
                    <th style="text-align: center; width: 30%;">Meistars</th>
                </tr>
            </thead>
            <tbody>';

$html .= '<tr>
                <td style="text-align: center;">' . $consultation['cons_date'] . '</td>
                <td style="text-align: center;">' . $consultation['cons_time'] . '</td>
                <td style="text-align: center;">' . $consultation['type'] . '</td>
                <td style="text-align: center;">' . $consultation['master_category'] . '</td>
                <td style="text-align: center;">' . $consultation['mFullName'] . '</td>
             </tr>';

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('Konsultācija.pdf', 'D');

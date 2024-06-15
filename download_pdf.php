<?php
require_once('tcpdf.php');
include 'includes/dbh.inc.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

// Fetch user information
$select = mysqli_query($conn, "SELECT * FROM `users` WHERE usersId = '$user_id'") or die('Query failed');
$fetch = mysqli_fetch_assoc($select);

// Fetch user's consultations
$consultations_query = mysqli_query($conn, "SELECT consultations.*, categories.categoryName AS master_category, masters.mFullName
                                            FROM `consultations`
                                            INNER JOIN `categories_masters` ON consultations.masterId = categories_masters.masterId
                                            INNER JOIN `categories` ON categories_masters.categoryId = categories.categoryId
                                            INNER JOIN `masters` ON consultations.masterId = masters.masterId
                                            WHERE consultations.usersId = '$user_id'
                                            ORDER BY consultations.cons_date DESC, consultations.cons_time DESC") or die('Query failed: ' . mysqli_error($conn));

// Create new PDF document
$pdf = new TCPDF();
$pdf->AddPage();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jūsu Vietne');
$pdf->SetTitle('Konsultāciju informācija');
$pdf->SetSubject('Konsultāciju detaļas');
$pdf->SetKeywords('TCPDF, PDF, konsultācija, lietotājs');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Konsultāciju informācija', 'Lietotāja konsultāciju detaļas');

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
$pdf->SetFont('dejavusans', '', 10);

// Add content
$html = '<h1>Lietotāja informācija</h1>';
$html .= '<p>Vārds: ' . $fetch['name'] . '</p>';
$html .= '<p>E-pasts: ' . $fetch['email'] . '</p>';
$html .= '<p>Telefona numurs: ' . $fetch['number'] . '</p>';

$html .= '<h2>Jūsu konsultācijas</h2>';
$html .= '<table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Datums</th>
                    <th>Laiks</th>
                    <th>Tips</th>
                    <th>Kategorija</th>
                    <th>Meistars</th>
                </tr>
            </thead>
            <tbody>';

while ($row = mysqli_fetch_assoc($consultations_query)) {
    $html .= '<tr>
                <td>' . $row['cons_date'] . '</td>
                <td>' . $row['cons_time'] . '</td>
                <td>' . $row['type'] . '</td>
                <td>' . $row['master_category'] . '</td>
                <td>' . $row['mFullName'] . '</td>
             </tr>';
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('konsultaciju_informacija.pdf', 'D');
?>

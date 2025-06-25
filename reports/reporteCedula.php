<?php
require('../fpdf186/fpdf.php');
require('../models/conexion.php');

$cedula = $_GET['cedula'];

$sqlSelect = "SELECT * FROM estudiantes WHERE cedula = $cedula";
$resultado = $conn->query($sqlSelect);

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTitle('Reporte Individual de Estudiante');
$pdf->Cell(0, 10, 'Reporte Individual de Estudiante', 0, 1, 'C');
$pdf->Ln();
$pdf->Cell(20, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Apellido', 1);
$pdf->Cell(30, 10, 'Cedula', 1);
$pdf->Cell(40, 10, 'Carrera', 1);
$pdf->Cell(60, 10, 'Email', 1);
$pdf->Cell(30, 10, 'Telefono', 1);
$pdf->Ln();

while ($row = $resultado->fetch_assoc()) {
    // Datos de la fila
    $data = [
        iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $row['nombres']),
        iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $row['apellidos']),
        iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $row['cedula']),
        iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $row['carrera']),
        iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $row['email']),
        iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $row['telefono'])
    ];
    // Anchos de cada columna
    $widths = [20, 40, 30, 40, 60, 30];

    // Calcular la altura máxima de la fila (en líneas)
    $maxLines = 1;
    $pdf->SetFont('Arial', '', 12);
    foreach ($data as $i => $txt) {
        // Solo para la columna email (índice 5) calculamos líneas extra
        if ($i == 5) {
            $lines = $pdf->GetStringWidth($txt) / ($widths[$i] - 2);
            $lines = ceil(strlen($txt) / 40); // Aproximación simple
            if ($lines > $maxLines) $maxLines = $lines ;
        }
    }
    $cellHeight = 10 * $maxLines;

    // Guardar posición inicial
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Dibujar celdas normales
    for ($i = 0; $i < 5; $i++) {
        $pdf->MultiCell($widths[$i], $cellHeight, $data[$i], 1, 'L', false);
        $pdf->SetXY($x += $widths[$i], $y);
    }
    // Teléfono
    $pdf->MultiCell($widths[5], $cellHeight, $data[5], 1, 'L', false);
    
    // Ir a la siguiente línea
    $pdf->SetXY($pdf->GetX() - array_sum($widths), $y + $cellHeight);
     
    $pdf->Ln();

}

$pdf->Output();
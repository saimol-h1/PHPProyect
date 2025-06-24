<?php
require('../fpdf186/fpdf.php');
require('../models/conexion.php');

$cedula = $_GET['cedula'];

$sqlSelect = "SELECT * FROM estudiantes WHERE cedula = '$cedula'";
$resultado = $conn->query($sqlSelect);

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTitle('Reporte General de Estudiantes');
$pdf->Cell(0, 10, 'Reporte General de Estudiantes', 0, 1, 'C');
$pdf->Ln();
$pdf->Cell(20, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Apellido', 1);
$pdf->Cell(30, 10, 'Cedula', 1);
$pdf->Cell(40, 10, 'Carrera', 1);
$pdf->Cell(60, 10, 'Email', 1);
$pdf->Cell(30, 10, 'Telefono', 1);
$pdf->Ln();
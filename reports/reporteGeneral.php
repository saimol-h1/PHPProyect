<?php
require('../fpdf186/fpdf.php');
require('../models/conexion.php');

// Clase personalizada para el PDF
class UniversityPDF extends FPDF
{
    // Encabezado de página
    function Header()
    {
        // Color universitario para el fondo del encabezado
        $this->SetFillColor(144, 27, 33); // Color #901B21 UTA
        $this->Rect(0, 0, 297, 35, 'F');

        // Logos en los extremos
        // Logo izquierdo
        $this->Image('https://res.cloudinary.com/dwwvecqnu/image/upload/v1751034779/logo-uta_gofi9e.png', 15, 5, 20, 27);


        // Texto del encabezado en blanco
        $this->SetTextColor(255, 255, 255);

        // Título principal (centrado entre los logos)
        $this->SetFont('Arial', 'B', 18);
        $this->SetY(8);
        $this->SetX(50); // Margen izquierdo para centrar entre logos
        $this->Cell(197, 8, utf8_decode('UNIVERSIDAD TÉCNICA DE AMBATO'), 0, 1, 'C');

        // Subtítulo
        $this->SetFont('Arial', '', 12);
        $this->SetX(50);
        $this->Cell(197, 6, utf8_decode('FACULTAD DE INGENIERÍA EN SISTEMAS'), 0, 1, 'C');
        $this->SetX(50);
        $this->Cell(197, 6, utf8_decode('SISTEMA DE GESTIÓN ESTUDIANTIL'), 0, 1, 'C');

        // Línea decorativa (centrada entre logos)
        $this->SetDrawColor(255, 255, 255);
        $this->SetLineWidth(1);
        $this->Line(60, 32, 237, 32);

        // Resetear colores
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);

        $this->Ln(15);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-25);

        // Línea decorativa
        $this->SetDrawColor(144, 27, 33);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 287, $this->GetY());

        // Información del pie
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Ln(3);
        $this->Cell(0, 4, utf8_decode('Universidad Técnica de Ambato - Av. Los Chasquis y Río Payamino'), 0, 1, 'C');
        $this->Cell(0, 4, utf8_decode('Teléfono: (03) 2521081 - Email: info@uta.edu.ec'), 0, 1, 'C');

        // Número de página
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 4, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'R');

        // Fecha de generación
        $this->SetX(10);
        $this->Cell(0, 4, 'Generado: ' . date('d/m/Y H:i:s'), 0, 0, 'L');
    }
}

// Obtener datos
$sqlSelect = "SELECT * FROM estudiantes ORDER BY apellidos, nombres";
$resultado = $conn->query($sqlSelect);
$totalEstudiantes = $resultado->num_rows;

// Crear PDF
$pdf = new UniversityPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetTitle(utf8_decode('Reporte General de Estudiantes - UTA'));
$pdf->SetAuthor(utf8_decode('Universidad Técnica de Ambato'));
$pdf->SetSubject(utf8_decode('Listado de Estudiantes Registrados'));

// Información del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(144, 27, 33);
$pdf->Cell(0, 10, utf8_decode('REPORTE GENERAL DE ESTUDIANTES'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5);

// Información estadística
$pdf->SetFillColor(240, 240, 240);
$pdf->Rect(10, $pdf->GetY(), 277, 15, 'F');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 6, utf8_decode('RESUMEN ESTADÍSTICO:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, 'Total de Estudiantes: ' . $totalEstudiantes, 0, 0, 'L');
$pdf->Cell(80, 6, utf8_decode('Fecha de Generación: ') . date('d/m/Y'), 0, 0, 'L');
date_default_timezone_set('America/Guayaquil');
$pdf->Cell(0, 6, 'Hora: ' . date('H:i:s'), 0, 1, 'L');
$pdf->Ln(10);

// Encabezados de tabla
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(144, 27, 33);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(128, 128, 128);

// Definir anchos de columnas optimizados
$pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'NOMBRES', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'APELLIDOS', 1, 0, 'C', true);
$pdf->Cell(25, 8, utf8_decode('CÉDULA'), 1, 0, 'C', true);
$pdf->Cell(40, 8, 'CARRERA', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'EMAIL', 1, 0, 'C', true);
$pdf->Cell(25, 8, utf8_decode('TELÉFONO'), 1, 0, 'C', true);
$pdf->Cell(15, 8, 'SEM.', 1, 0, 'C', true);
$pdf->Cell(27, 8, 'F. NACIMIENTO', 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 8);

// Contador de filas para alternar colores
$contador = 0;

while ($row = $resultado->fetch_assoc()) {
    // Alternar colores de fila para mejor legibilidad
    if ($contador % 2 == 0) {
        $pdf->SetFillColor(250, 250, 250);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }

    // Convertir datos para evitar problemas de codificación
    $id = $row['id'] ?? 'N/A';
    $nombres = utf8_decode($row['nombres'] ?? 'N/A');
    $apellidos = utf8_decode($row['apellidos'] ?? 'N/A');
    $cedula = $row['cedula'] ?? 'N/A';
    $carrera = utf8_decode($row['carrera'] ?? 'N/A');
    $email = utf8_decode($row['email'] ?? 'N/A');
    $telefono = $row['telefono'] ?? 'N/A';
    $semestre = $row['semestre'] ?? 'N/A';
    $fecha_nacimiento = $row['fecha_nacimiento'] ?? 'N/A';

    // Formatear fecha de nacimiento
    if ($fecha_nacimiento != 'N/A' && $fecha_nacimiento != '') {
        $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        if ($fecha_obj) {
            $fecha_nacimiento = $fecha_obj->format('d/m/Y');
        }
    }

    // Truncar texto largo si es necesario
    if (strlen($nombres) > 20) $nombres = substr($nombres, 0, 17) . '...';
    if (strlen($apellidos) > 20) $apellidos = substr($apellidos, 0, 17) . '...';
    if (strlen($carrera) > 25) $carrera = substr($carrera, 0, 22) . '...';
    if (strlen($email) > 30) $email = substr($email, 0, 27) . '...';

    // Verificar si necesitamos una nueva página
    if ($pdf->GetY() > 180) {
        $pdf->AddPage('L');

        // Repetir encabezados
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(144, 27, 33);
        $pdf->SetTextColor(255, 255, 255);

        $pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'NOMBRES', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'APELLIDOS', 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('CÉDULA'), 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'CARRERA', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'EMAIL', 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('TELÉFONO'), 1, 0, 'C', true);
        $pdf->Cell(15, 8, 'SEM.', 1, 0, 'C', true);
        $pdf->Cell(27, 8, 'F. NACIMIENTO', 1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 8);
    }

    // Imprimir fila de datos
    $pdf->Cell(15, 7, $id, 1, 0, 'C', true);
    $pdf->Cell(35, 7, $nombres, 1, 0, 'L', true);
    $pdf->Cell(35, 7, $apellidos, 1, 0, 'L', true);
    $pdf->Cell(25, 7, $cedula, 1, 0, 'C', true);
    $pdf->Cell(40, 7, $carrera, 1, 0, 'L', true);
    $pdf->Cell(50, 7, $email, 1, 0, 'L', true);
    $pdf->Cell(25, 7, $telefono, 1, 0, 'C', true);
    $pdf->Cell(15, 7, $semestre, 1, 0, 'C', true);
    $pdf->Cell(27, 7, $fecha_nacimiento, 1, 1, 'C', true);

    $contador++;
}

// Si no hay estudiantes
if ($totalEstudiantes == 0) {
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetTextColor(128, 128, 128);
    $pdf->Cell(0, 20, utf8_decode('No hay estudiantes registrados en el sistema'), 0, 1, 'C');
}

// Resumen final
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(144, 27, 33);
$pdf->Cell(0, 6, utf8_decode('INFORMACIÓN ADICIONAL:'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, utf8_decode('* Este reporte contiene la información completa de todos los estudiantes registrados en el sistema.'), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode('* Los datos están ordenados alfabéticamente por apellidos y nombres.'), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode('* Para consultas específicas, contacte con la Secretaría Académica.'), 0, 1, 'L');

// Espacio para firmas
$pdf->Ln(15);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(90, 5, '', 0, 0, 'C');
$pdf->Cell(90, 5, '', 0, 1, 'C');
$pdf->Line(30, $pdf->GetY(), 80, $pdf->GetY());
$pdf->Line(150, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(2);
$pdf->Cell(90, 5, utf8_decode('Secretario(a) Académico(a)'), 0, 0, 'C');
$pdf->Cell(90, 5, utf8_decode('Director(a) de Carrera'), 0, 1, 'C');

$pdf->Output('I', 'Reporte_General_Estudiantes_UTA.pdf');

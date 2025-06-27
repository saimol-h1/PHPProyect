<?php
require('../fpdf186/fpdf.php');
require('../models/conexion.php');

// Clase personalizada para el PDF
class UniversityStudentPDF extends FPDF
{
    private $estudiante;

    public function setEstudiante($estudiante)
    {
        $this->estudiante = $estudiante;
    }

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

// Validar y obtener cédula
$cedula = $_GET['cedula'] ?? '';
if (empty($cedula)) {
    die('Error: No se proporcionó número de cédula');
}

// Preparar consulta segura
$sqlSelect = "SELECT * FROM estudiantes WHERE cedula = ?";
$stmt = $conn->prepare($sqlSelect);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si existe el estudiante
if ($resultado->num_rows == 0) {
    die('Error: No se encontró estudiante con cédula ' . htmlspecialchars($cedula));
}

$estudiante = $resultado->fetch_assoc();

// Crear PDF
$pdf = new UniversityStudentPDF();
$pdf->setEstudiante($estudiante);
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetTitle(utf8_decode('Reporte Individual de Estudiante - UTA'));
$pdf->SetAuthor(utf8_decode('Universidad Técnica de Ambato'));
$pdf->SetSubject(utf8_decode('Información del Estudiante: ' . $estudiante['nombres'] . ' ' . $estudiante['apellidos']));

// Título del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(144, 27, 33);
$pdf->Cell(0, 10, utf8_decode('REPORTE INDIVIDUAL DE ESTUDIANTE'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5);

// Información del reporte
$pdf->SetFillColor(240, 240, 240);
$pdf->Rect(10, $pdf->GetY(), 277, 15, 'F');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 6, utf8_decode('INFORMACIÓN DEL REPORTE:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(80, 6, utf8_decode('Cédula Consultada: ') . $cedula, 0, 0, 'L');
$pdf->Cell(80, 6, utf8_decode('Fecha de Generación: ') . date('d/m/Y'), 0, 0, 'L');
$pdf->Cell(0, 6, 'Hora: ' . date('H:i:s'), 0, 1, 'L');
$pdf->Ln(15);

// Sección de datos personales
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(144, 27, 33);
$pdf->Cell(0, 8, utf8_decode('DATOS PERSONALES'), 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5);

// Convertir datos para caracteres especiales
$nombres = utf8_decode($estudiante['nombres'] ?? 'N/A');
$apellidos = utf8_decode($estudiante['apellidos'] ?? 'N/A');
$cedula_est = $estudiante['cedula'] ?? 'N/A';
$carrera = utf8_decode($estudiante['carrera'] ?? 'N/A');
$email = utf8_decode($estudiante['email'] ?? 'N/A');
$telefono = $estudiante['telefono'] ?? 'N/A';
$semestre = $estudiante['semestre'] ?? 'N/A';
$direccion = utf8_decode($estudiante['direccion'] ?? 'N/A');
$fecha_nacimiento = $estudiante['fecha_nacimiento'] ?? 'N/A';

// Formatear fecha de nacimiento
if ($fecha_nacimiento != 'N/A' && $fecha_nacimiento != '') {
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    if ($fecha_obj) {
        $fecha_nacimiento = $fecha_obj->format('d/m/Y');
        // Calcular edad
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_obj)->y;
    }
}

// Layout en dos columnas para información personal
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(250, 250, 250);

// Columna izquierda
$startY = $pdf->GetY();
$pdf->Cell(135, 8, '', 1, 0, 'L', true); // Celda contenedora izquierda
$pdf->SetXY(10, $startY);

$pdf->Cell(30, 8, 'ID:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(105, 8, $estudiante['id'], 0, 1, 'L');

$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'Nombres:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(105, 8, $nombres, 0, 1, 'L');

$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'Apellidos:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(105, 8, $apellidos, 0, 1, 'L');

$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, utf8_decode('Cédula:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(105, 8, $cedula_est, 0, 1, 'L');

$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'Carrera:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(105, 8, $carrera, 0, 1, 'L');

// Columna derecha
$pdf->SetXY(145, $startY);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(142, 8, '', 1, 0, 'L', true); // Celda contenedora derecha
$pdf->SetXY(145, $startY);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(112, 8, $email, 0, 1, 'L');

$pdf->SetX(145);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(112, 8, $telefono, 0, 1, 'L');

$pdf->SetX(145);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'Semestre:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(112, 8, $semestre, 0, 1, 'L');

$pdf->SetX(145);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'F. Nacimiento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$edad_texto = isset($edad) ? " ($edad " . utf8_decode('años') . ")" : "";
$pdf->Cell(112, 8, $fecha_nacimiento . $edad_texto, 0, 1, 'L');

$pdf->SetX(145);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, utf8_decode('Dirección:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(112, 8, $direccion, 0, 1, 'L');

$pdf->Ln(15);

// Sección de información académica
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(144, 27, 33);
$pdf->Cell(0, 8, utf8_decode('INFORMACIÓN ACADÉMICA'), 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5);

// Tabla de información académica
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(144, 27, 33);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(90, 8, utf8_decode('ESTADO ACADÉMICO'), 1, 0, 'C', true);
$pdf->Cell(90, 8, utf8_decode('INFORMACIÓN DE CONTACTO'), 1, 0, 'C', true);
$pdf->Cell(87, 8, 'DATOS ADICIONALES', 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(250, 250, 250);

// Fila 1
$pdf->Cell(90, 8, utf8_decode('Semestre Actual: ') . $semestre, 1, 0, 'L', true);
$pdf->Cell(90, 8, 'Email: ' . $email, 1, 0, 'L', true);
$pdf->Cell(87, 8, utf8_decode('Fecha Registro: ') . date('d/m/Y'), 1, 1, 'L', true);

// Fila 2
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(90, 8, 'Carrera: ' . $carrera, 1, 0, 'L', true);
$pdf->Cell(90, 8, utf8_decode('Teléfono: ') . $telefono, 1, 0, 'L', true);
$pdf->Cell(87, 8, 'ID Sistema: ' . $estudiante['id'], 1, 1, 'L', true);

$pdf->Ln(20);

// Sección de observaciones
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(144, 27, 33);
$pdf->Cell(0, 8, 'OBSERVACIONES Y NOTAS', 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode('* Este reporte contiene la información completa del estudiante consultado.'), 0, 1, 'L');
$pdf->Cell(0, 6, utf8_decode('* Los datos mostrados corresponden al estado actual en el sistema.'), 0, 1, 'L');
$pdf->Cell(0, 6, utf8_decode('* Para modificaciones, contacte con la Secretaría Académica.'), 0, 1, 'L');
$pdf->Cell(0, 6, utf8_decode('* Este documento es válido solo para consulta informativa.'), 0, 1, 'L');

// Espacio para firmas
$pdf->Ln(25);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(90, 5, '', 0, 0, 'C');
$pdf->Cell(90, 5, '', 0, 1, 'C');
$pdf->Line(30, $pdf->GetY(), 80, $pdf->GetY());
$pdf->Line(150, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(2);
$pdf->Cell(90, 5, utf8_decode('Secretario(a) Académico(a)'), 0, 0, 'C');
$pdf->Cell(90, 5, utf8_decode('Director(a) de Carrera'), 0, 1, 'C');

$pdf->Output('I', 'Reporte_Estudiante_' . $cedula . '_UTA.pdf');

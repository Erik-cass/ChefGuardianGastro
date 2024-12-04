<?php
require('./fpdf186/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->Image('./fpdf186/img/header.png', 10, 10, 190);
        $this->SetY($this->GetY() + 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, utf8_decode('Lista de Alumnos'), 0, 1, 'C');
        $this->Ln(10); // Espacio adicional después del título
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }

    function ImprovedTable($header, $data) {
        $this->SetFont('Arial', 'B', 12);
        
        // Column widths (excluding image column)
        $w = array(18, 22, 50, 45, 18, 35, 18, 40); // Ajustar según necesidad
        
        // Header
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 10, utf8_decode($header[$i]), 1);
        }
        $this->Ln();
        
        // Data
        $this->SetFont('Arial', '', 10);
        foreach ($data as $row) {
            $this->Cell($w[0], 10, utf8_decode($row['id_usuarios']), 1);
            $this->Cell($w[1], 10, utf8_decode($row['matricula']), 1);
            $this->Cell($w[2], 10, utf8_decode($row['nombre']), 1);
            $this->Cell($w[3], 10, utf8_decode($row['email']), 1);
            $this->Cell($w[4], 10, utf8_decode($row['grupo']), 1);
            $this->Cell($w[5], 10, utf8_decode($row['cuatrimestre']), 1);
            $this->Cell($w[6], 10, utf8_decode($row['rol']), 1);
            $this->Cell($w[7], 10, utf8_decode($row['fecha']), 1);


            $this->Ln();
        }
    }
}

// Crear instancia de PDF
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();

// Consultar datos de la base de datos
include '../includes/config.php';

$query = "SELECT id_usuarios, matricula, nombre, email, grupo, cuatrimestre, rol, fecha FROM alumnos WHERE rol= 'alumno'";
$result = $conn->query($query);

$header = array('ID', 'Matricula', 'Nombre', 'Email', 'Grupo', 'Cuatrimestre', 'Rol', 'Fecha');
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Preparar datos para el PDF
        $data[] = array(
            'id_usuarios' => $row['id_usuarios'], 
            'matricula' => $row['matricula'], 
            'nombre' => $row['nombre'], 
            'email' => $row['email'], 
            'grupo' => $row['grupo'],
            'cuatrimestre' => $row['cuatrimestre'],
            'rol' => $row['rol'],
            'fecha' => $row['fecha']
        );
    }
} else {
    $data[] = array('id_usuarios' => 'No disponible', 'matricula' => 'No disponible', 'nombre' => 'No disponible', 'email' => 'No disponible', 'grupo' => 'No disponible', 'cuatrimestre' => 'No disponible', 'rol' => 'rol', 'fecha' => 'fecha');
}

$pdf->ImprovedTable($header, $data);
$pdf->Output(); // Muestra el PDF en el navegador

$conn->close();
?>


<?php
require('./fpdf186/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->Image('./fpdf186/img/header.png', 10, 10, 190 );
        $this->SetY($this->GetY() + 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, utf8_decode('Lista de Artículos'), 0, 1, 'C');
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
        $w = array(18, 50, 23, 100, 25, 40); // Ajustar según necesidad
        
        // Header
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 10, utf8_decode($header[$i]), 1);
        }
        $this->Ln();
        
        // Data
        $this->SetFont('Arial', '', 11);
        foreach ($data as $row) {
            $this->Cell($w[0], 10, utf8_decode($row['id_articulo']), 1);
            $this->Cell($w[1], 10, utf8_decode($row['nombre']), 1);
            $this->Cell($w[2], 10, utf8_decode($row['categoria']), 1);
            $this->Cell($w[3], 10, utf8_decode($row['descripcion']), 1);
            $this->Cell($w[4], 10, utf8_decode($row['cantidad']), 1);
            $this->Cell($w[5], 10, utf8_decode($row['fh']), 1);
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

$query = "SELECT id_articulo, nombre, categoria, descripcion, cantidad, fh FROM articulos";
$result = $conn->query($query);

$header = array('ID', 'Nombre', 'Categoría', 'Descripción', 'Cantidad', 'Fecha');
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Preparar datos para el PDF
        $data[] = array(
            'id_articulo' => $row['id_articulo'], 
            'nombre' => $row['nombre'], 
            'categoria' => $row['categoria'], 
            'descripcion' => $row['descripcion'], 
            'cantidad' => $row['cantidad'],
            'fh' => $row['fh']
        );
    }
} else {
    $data[] = array('id_articulo' => 'No disponible', 'nombre' => 'No disponible', 'categoria' => 'No disponible', 'descripcion' => 'No disponible', 'cantidad' => 'No disponible', 'fh' => 'No disponible');
}

$pdf->ImprovedTable($header, $data);
$pdf->Output(); // Muestra el PDF en el navegador

$conn->close();
?>


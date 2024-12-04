<?php
header('Content-Type: text/html; charset=utf-8');
require('../csv/fpdf186/fpdf.php');

// Conexión a la base de datos
include '../includes/config.php';

class PDF extends FPDF {
    function Header() {
        if (file_exists('../csv/fpdf186/img/header.png')) {
            $this->Image('../csv/fpdf186/img/header.png', 10, 10, 190);
        }
        $this->SetY($this->GetY() + 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, utf8_decode('Reporte de Deudores de Material'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }

    function ImprovedTable($data) {
        $this->SetFont('Arial', 'B', 9);

        // Configuración de la tabla
        $this->Cell(50, 10, 'Nombre del Alumno', 1, 0, 'C');
        $this->Cell(30, 10, 'Grupo', 1, 0, 'C');
        $this->Cell(50, 10, 'Material', 1, 0, 'C');
        $this->Cell(20, 10, 'Cantidad', 1, 0, 'C');
        $this->Cell(40, 10, 'Fecha de Entrega', 1, 1, 'C');

        $this->SetFont('Arial', '', 8);

        foreach ($data as $row) {
            $this->Cell(50, 8, utf8_decode($row['nombre']), 1, 0, 'C');
            $this->Cell(30, 8, utf8_decode($row['grupo']), 1, 0, 'C');
            $this->Cell(50, 8, utf8_decode($row['nombre_material']), 1, 0, 'C');
            $this->Cell(20, 8, $row['cantidad'], 1, 0, 'C');
            $this->Cell(40, 8, $row['fecha_entrega'], 1, 1, 'C');
        }
    }

    function AddWarning($message) {
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(5);
        $this->Cell(0, 10, utf8_decode('Sanción:'), 0, 1);
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 6, utf8_decode($message));
        $this->Ln(5);
    }
}

// Crear objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Consulta a la base de datos
$query = "
    SELECT 
        a.nombre,
        a.grupo,
        p.nombre AS nombre_material,
        p.cantidad,
        p.fecha_entrega
    FROM alumnos a
    INNER JOIN pedidos p ON a.id_usuarios = p.id_usuarios
    WHERE p.estado = 'pendiente por devolver' 
    AND DATEDIFF(NOW(), p.fecha_limite) > 0
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'nombre' => $row['nombre'],
            'grupo' => $row['grupo'],
            'nombre_material' => $row['nombre_material'],
            'cantidad' => $row['cantidad'],
            'fecha_entrega' => $row['fecha_entrega'],
        ];
    }

    $pdf->ImprovedTable($data);
    $pdf->AddWarning("Los alumnos enlistados no podrán solicitar más material hasta que regresen o repongan el material adeudado.");
} else {
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, utf8_decode('No hay alumnos con material pendiente de devolución.'), 0, 1, 'C');
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Deudores_Material.pdf');
?>

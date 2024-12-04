<?php
header('Content-Type: text/html; charset=utf-8');
require('../csv/fpdf186/fpdf.php');

class PDF extends FPDF {
    function Header() {
        if(file_exists('../csv/fpdf186/img/header.png')){
            $this->Image('../csv/fpdf186/img/header.png', 10, 10, 190);
        }
        $this->SetY($this->GetY() + 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, utf8_decode('Formato de Requisicion'), 0, 1, 'C');
    }

    function Footer() {
        $this->Sety(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Pagina ') . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }

    function ImprovedTable($data){
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);

        // Alumno Info
        foreach ($data['alumno'] as $key => $value) {
            $this->Cell(45, 10, utf8_decode(ucfirst($key)) . ':', 1);
            $this->Cell(150, 10, utf8_decode($value), 1);
            $this->Ln();
        }

        // Header for Articles
        $this->Ln(10);
        $this->Cell(45, 10, 'Nombre', 1);
        $this->Cell(45, 10, 'Categoria', 1);
        $this->Cell(20, 10, 'Cantidad', 1);
        $this->Cell(50, 10, 'Descripcion', 1);
        $this->Cell(20, 10, 'Estado', 1);
        $this->Ln();

        // Articles Data
        foreach ($data['articulos'] as $articulo) {
            $this->Cell(45, 10, utf8_decode($articulo['nombre']), 1);
            $this->Cell(45, 10, utf8_decode($articulo['categoria']), 1);
            $this->Cell(20, 10, utf8_decode($articulo['cantidad']), 1);
            $this->Cell(50, 10, utf8_decode($articulo['descripcion']), 1);
            $this->Cell(20, 10, utf8_decode($articulo['estado']), 1);
            $this->Ln();
        }
    }
}

function LoadData($id_usuarios) {
    include_once("../includes/config.php");
    $conn->set_charset("utf8");

    // Query to get alumno data
    $query_alumno = "
        SELECT 
            a.nombre AS nombre_alumno,
            a.grupo AS grupo,
            a.matricula AS matricula,
            p.asignatura AS asignatura,
            p.profesor AS profesor,
            p.fh AS fecha
        FROM alumnos a
        JOIN pedidos p ON a.id_usuarios = p.id_usuarios
        WHERE a.id_usuarios = ?
        LIMIT 1;
    ";

    $stmt_alumno = $conn->prepare($query_alumno);
    $stmt_alumno->bind_param("i", $id_usuarios);
    $stmt_alumno->execute();
    $result_alumno = $stmt_alumno->get_result();
    $data['alumno'] = $result_alumno->fetch_assoc();

    if (!$data['alumno']) {
        throw new Exception('No se encontraron datos del alumno. id_usuarios: ' . $id_usuarios);
    }

    // Query to get articles data
    $query_articulos = "
        SELECT 
            nombre,
            categoria,
            cantidad,
            descripcion,
            estado
        FROM pedidos
        WHERE id_usuarios = ?
    ";

    $stmt_articulos = $conn->prepare($query_articulos);
    $stmt_articulos->bind_param("i", $id_usuarios);
    $stmt_articulos->execute();
    $result_articulos = $stmt_articulos->get_result();
    $data['articulos'] = [];

    while ($row = $result_articulos->fetch_assoc()) {
        $data['articulos'][] = $row;
    }

    if (empty($data['articulos'])) {
        throw new Exception('No se encontraron artículos para este alumno. id_usuarios: ' . $id_usuarios);
    }

    $conn->close();
    return $data;
}

if (isset($_GET['id'])){
    $id_usuarios = intval($_GET['id']);
    try {
        $data = LoadData($id_usuarios);
        $pdf = new PDF('P', 'mm', 'Letter');
        $pdf->AliasNbPages();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->AddPage();
        $pdf->ImprovedTable($data);
        $pdf->Output();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

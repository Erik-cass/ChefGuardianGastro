<?php
header('Content-Type: text/html; charset=utf-8');
require('../csv/fpdf186/fpdf.php');

class PDF extends FPDF {
    // Cabecera del documento
    function Header() {
        if (file_exists('../csv/fpdf186/img/header.png')) {
            $this->Image('../csv/fpdf186/img/header.png', 10, 10, 190); // Imagen del encabezado
        }
        $this->SetY($this->GetY() + 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, utf8_decode('Formato de Requisición'), 0, 1, 'C');
    }

    // Pie de página
    function Footer() {
        // Nota
        $this->SetY(-50);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, utf8_decode(
            'Nota: En el caso de algún faltante tendrán como máximo 5 días hábiles para la reposición doble del material, de lo contrario se levantará ' .
            'un oficio de extrañamiento por medio de su director y no se le permitirá acceso a prácticas.'
        ), 0, 'L');

        // Espacio antes de las firmas
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);

        // Firmas
        $this->Cell(80, 10, '__________________________', 0, 0, 'C');
        $this->Cell(30, 10, '', 0, 0); // Espacio entre firmas
        $this->Cell(80, 10, '__________________________', 0, 1, 'C');

        $this->Cell(80, 10, 'Nombre y Firma del docente que reviso y valido', 0, 0, 'C');
        $this->Cell(30, 10, '', 0, 0); // Espacio entre firmas
        $this->Cell(80, 10, 'Nombre y Firma del responsable de talleres de Gastronomia', 0, 0, 'C');
    }

    // Método para generar la tabla
    function ImprovedTable($data) {
        $this->SetFont('Arial', 'B', 11);
        $this->Ln(10);
        $this->SetFont('Arial', '', 11);

        // Información del alumno
        foreach ($data['alumno'] as $key => $value) {
            $this->Cell(45, 10, utf8_decode(ucfirst($key)) . ':', 1);
            $this->Cell(150, 10, utf8_decode($value), 1);
            $this->Ln();
        }

        // Encabezado de la tabla de artículos
        $this->Ln(10);
        $this->Cell(45, 10, 'Nombre', 1);
        $this->Cell(28, 10, 'Categoria', 1);
        $this->Cell(20, 10, 'Cantidad', 1);
        $this->Cell(60, 10, 'Descripcion', 1);
        $this->Cell(42, 10, 'Estado', 1);
        $this->Ln();

        // Datos de los artículos
        foreach ($data['articulos'] as $articulo) {
            $this->Cell(45, 10, utf8_decode($articulo['nombre']), 1);
            $this->Cell(28, 10, utf8_decode($articulo['categoria']), 1);
            $this->Cell(20, 10, utf8_decode($articulo['cantidad']), 1);
            $this->Cell(60, 10, utf8_decode($articulo['descripcion']), 1);
            $this->Cell(42, 10, utf8_decode($articulo['estado']), 1);
            $this->Ln();
        }
    }
}

// Función para cargar los datos
function LoadData($id_usuarios) {
    include_once("../includes/config.php");
    $conn->set_charset("utf8");

    // Query para obtener datos del alumno
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

    // Query para obtener datos de artículos
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

// Lógica para generar el PDF
if (isset($_GET['id'])) {
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

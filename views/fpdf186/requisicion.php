<?php
header('Content-Type: text/html; charset=utf-8');
require('fpdf.php');

class  PDF extends FPDF {
	function Header() {
		$this->Image('img/header.png', 10, 10, 190);
		$this->SetY($this->GetY() + 25);
		$this->SetFont('Arial', 'B', 12);
		$this->Cell(0, 6, utf8_decode('Formato de Requisicion'), 0, 1, 'C');
	}


	function Footer() {
		$this->Sety(-15);
		$this->SetFont('Arial', 'I', 8);
		$this->Cell(0, 10, utf8_decode('Pagina ') . $this->PageNo() . 'de {nb}', 0, 0, 'C');

	}

	function ImprovedTable($data){
		$this->SetFont('Arial', 'B', 10);
		$this->Ln(0);
		$this->SetFont('Arial', '', 10);
		$leftColumnWidth = 45;
		$righColumnWidth = 150;

		$first = true;
		foreach ($data as $row) {
			if (!$first){
				$this->AddPage();

			}
			foreach ($row as $key => $value) {
				$this->cell($leftColumnWidth, 10, utf8_decode(ucfirst($key)) . ':', 1);
				$this->cell($righColumnWidth, 10, utf8_encode($value), 1);
				$this->Ln();
			}
			$first=false;
		}
	}
}

function LoadData($id_alumno) {
	include_once("../config.php");
	$conn->set_charset("utf8");

	$query = "
		SELECT
			r.fecha_requisicion AS fecha_requisicion,
			a.nombre AS nombre_alumno,
			r.id_grupo AS id_grupo_alumno,
			r.asignatura AS nombre_asignatura,
			r.progama_educativo AS progama_educativo,
			r.profesor AS nombre_profesor,
			s.id_alumno AS id_alumno,
			s.cantidad AS cantidad_articulo,
			s.articulo AS articulo_solicitado,
			s.fecha_requisicion AS fecha_requisicion_articulo,
			s.observacion AS observacion_articulo,
			s.faltantes AS faltantes_articulo
		FROM
			requisicion r 
		JOIN
			alumnoss a ON r.id_alumno = a.id_alumno
		JOIN 
			solicitud s ON r.id_alumno =s.id_alumno
		WHERE 
			a.id_alumno = ?
		ORDER BY 
			r.fecha_requisicion, a.nombre;

	";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id_alumno);
	$stmt->execute();
	$result = $stmt->get_result();
	$data = [];

	while ($row = $result->fetch_assoc()) {
		$data[] = $row;

	}
	$conn->close();
	return $data;
}

if (isset($_GET['id'])){
	$id_alumno = intval($_GET['id']);
	$data = LoadData($id_alumno);
	$pdf = new PDF('P', 'mm', 'Letter');
	$pdf->AliasNbPages();
	$pdf->SetMargins(10, 10, 10);
	$pdf->SetFont('Arial', '', 12);
	$pdf->AddPage();
	$pdf->ImprovedTable($data);
	$pdf->Output();
}
?>
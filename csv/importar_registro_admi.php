<?php
// Configuracion de la BD 
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "proyectogastro";

// Crea conexion a la BD
$conn = new mysqli($hostname, $username, $password, $dbname);

// Configura la codificacion de caracteres a UTF8 
// $conn-> set_charset("utf8mb4");

// Verifica la conexion $hostname, $username, $password, $dbname
if ($conn->connect_error) { 
	die("connection failed: " . $conn->connect_error);
}


if (isset($_POST['submit'])) {
	if (isset($_FILES['file']) && $_FILES['file']['error'] == 0){
		require_once 'Classes/PHPExcel.php';
		$file = $_FILES['file']['tmp_name'];
		$excelReader = PHPExcel_IOFactory::createReaderForFile($file);
		$excelObj = $excelReader->load($file);
		$sheet = $excelObj->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$insertedCount = 0;
		$duplicateCount = 0;

		for ($row = 2; $row <= $highestRow; $row++) {
			$matricula = $sheet->getCell('A' . $row)->getValue();
			$nombre = $sheet->getCell('B' . $row)->getValue();
			$email = $sheet->getCell('C' . $row)->getValue();
			$password = $sheet->getCell('D' . $row)->getValue();
			$grupo = $sheet->getCell('E' . $row)->getValue();
			$cuatrimestre = $sheet->getCell('F' . $row)->getValue();
			$rol = $sheet->getCell('G' . $row)->getValue();


			$checkQuery = "SELECT * FROM alumnos WHERE email='$email'";

			$result = $conn->query($checkQuery);




			if ($result->num_rows ==0){
				$insertQuery = "INSERT INTO alumnos (matricula, nombre, email, password, grupo, cuatrimestre, rol) VALUES ('$matricula', '$nombre', '$email', '$password', '$grupo', '$cuatrimestre','$rol')";
				if ($conn->query($insertQuery) === TRUE) {
					$insertedCount++;
				}
			} else {	
				$duplicateCount++;
			}
		}

		echo "<script>
			alert('Registros insertados: $insertedCount.Registros duplicados: $duplicateCount.');
			window.location.href = '../views/registeralumadmi.html';
		</script>";
	} else { 
		echo "Error al subir el archivo";
	}
}

$conn->close();
?>
<?php 

try {
	$conexion = new PDO('mysql:host=YOUR_DB_HOSTING;dbname=YOUR_DB_NAME', 'YOUR_DB_USER_NAME', 'YOUR_DB_PASSWORD');

} catch (PDOException $e) {
	echo "ERROR: " . $e->getMessage();
	die();
}

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1 ;
$postPorPagina = 5;

$inicio = ($pagina > 1) ? ($pagina * $postPorPagina - $postPorPagina) : 0;

$articulos = $conexion->prepare("
	SELECT SQL_CALC_FOUND_ROWS * FROM articulos 
	LIMIT $inicio, $postPorPagina
"); // CHANGE articulos BY YOUR TABLE NAME. SQL_CAL is to extract number of articles and is used to later calculate number of pages needed

$articulos->execute();
$articulos = $articulos->fetchAll();

if (!$articulos) {
	header('Location: index.php');
}

$totalArticulos = $conexion->query('SELECT FOUND_ROWS() as total');
$totalArticulos = $totalArticulos->fetch()['total'];

$numeroPaginas = ceil($totalArticulos / $postPorPagina);

 require 'index.view.php';


?>
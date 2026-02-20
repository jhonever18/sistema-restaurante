

  <?php
  header('Content-Type: application/json');
include '../conexion/conectarBD.php';

$sql = "SELECT rol_id, rol_desc FROM roles WHERE rol_desc != 'cliente'";
$resultado = mysqli_query($connect, $sql);

$roles = [];

while ($row = mysqli_fetch_assoc($resultado)) {
    $roles[] = $row;
}

echo json_encode($roles);
?>

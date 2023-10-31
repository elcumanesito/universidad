<?php
session_start();
require_once "../config/database.php";

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'maestros') {
    header("Location: maestros_crud.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'alumnos') {
    header("Location: alumnos_crud.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'permisos') {
    header("Location: permisos_crud.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'clases') {
    header("Location: clases_crud.php");
    exit();
}

// Leer Clases
$query_leer_clases = "SELECT * FROM clases";
$statement_leer_clases = $pdo->prepare($query_leer_clases);
$statement_leer_clases->execute();
$clases_disponibles = $statement_leer_clases->fetchAll(PDO::FETCH_ASSOC);

// Leer Maestro
$query_leer_maestros = "SELECT * FROM maestros";
$statement_leer_maestros = $pdo->prepare($query_leer_maestros);
$statement_leer_maestros->execute();
$maestros = $statement_leer_maestros->fetchAll(PDO::FETCH_ASSOC);

// Eliminar Alumno
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_maestro"])) {
    $usuario_id = $_POST["eliminar_maestro"];

    // Eliminar de la tabla de maestros
    $query_eliminar_maestro = "DELETE FROM maestros WHERE usuario_id = :usuario_id";
    $statement_eliminar_maestro = $pdo->prepare($query_eliminar_maestro);
    $statement_eliminar_maestro->bindParam("usuario_id", $usuario_id);
    $statement_eliminar_maestro->execute();

    // Eliminar de la tabla de usuarios
    $query_eliminar_usuario = "DELETE FROM usuarios WHERE id = :usuario_id";
    $statement_eliminar_usuario = $pdo->prepare($query_eliminar_usuario);
    $statement_eliminar_usuario->bindParam("usuario_id", $usuario_id);
    $statement_eliminar_usuario->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div>
            <div>

            </div>
            <div>
                <h5>admin</h5>
                <h5>Administrador</h5>
            </div>
            <div>
                <h5>MENU ADMINISTRACION</h5>
                <p><a href="?accion=permisos">Permisos</a></p>
                <p><a href="?accion=maestros">Maestros</a></p>
                <p><a href="?accion=alumnos">Alumnos</a></p>
                <p><a href="?accion=clases">Clases</a></p>
            </div>
            <h2>CRUD de Maestros</h2>
        <button onclick="abrirModal()">Agregar Maestro</button>

            <table id="tablaMaestros">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Direccion</th>
                    <th>Fec. de Nacimiento</th>
                    <th>Clase asignada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maestros as $maestro) : ?>
                    <tr>
                        <td><?= $maestro["id"] ?></td>
                        <td><?= $maestro["nombre"] ?></td>
                        <td><?= $maestro["correo"] ?></td>
                        <td><?= $maestro["direccion"] ?></td>
                        <td><?= $maestro["fecha_nacimiento"] ?></td>
                        <td><?= $maestro["clase_asignada"] ?></td>
                        <td>
                            <button onclick="eliminarMaestro(<?= $maestro['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>

            <div id="modal" style="display: none;">
        <form id="formAgregarMaestro" action="/handle_db/insert_maestro.php" method="POST">
        <label>Nombre: <input type="text" name="nombre"></label><br>
        <label>Email: <input type="text" name="correo"></label><br>
        <label>Direccion: <input type="text" name="direccion"></label><br>
        <label>Fec. de Nacimiento: <input type="text" name="fecha_nacimiento"></label><br>
        <label>Clase asignada:
                <select name="clase_asignada">
                    <?php foreach ($clases_disponibles as $clase) : ?>
                        <!-- Verificar si la clase ya está asignada a algún maestro -->
                        <?php
                            $query_maestro_clase = "SELECT * FROM maestros WHERE clase_asignada = :nombre";
                            $statement_maestro_clase = $pdo->prepare($query_maestro_clase);
                            $statement_maestro_clase->bindParam(":nombre", $clase["nombre"]);
                            $statement_maestro_clase->execute();
                            $maestro_asignado = $statement_maestro_clase->fetch(PDO::FETCH_ASSOC);
                        ?>

                        <!-- Mostrar la clase solo si no está asignada a ningún maestro -->
                        <?php if (!$maestro_asignado) : ?>
                            <option value="<?= $clase['nombre'] ?>"><?= $clase['nombre'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </label>
        <button type="submit">Guardar</button>
        <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
</div>



</div>


<script>
            function abrirModal() {
                document.getElementById('modal').style.display = 'block';
            }

            function cerrarModal() {
                document.getElementById('modal').style.display = 'none';
            }

            // Función para eliminar maestro
            function eliminarMaestro(maestroId) {
                if (confirm("¿Estás seguro de que quieres eliminar este maestro?")) {
                    // Crear un formulario dinámico
                    var form = document.createElement("form");
                    form.method = "POST";
                    
                    // Agregar un input oculto con el valor del ID del maestro
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "eliminar_maestro";
                    input.value = maestroId;
                    form.appendChild(input);

                    // Adjuntar el formulario al cuerpo del documento
                    document.body.appendChild(form);

                    // Enviar el formulario
                    form.submit();
                }
            }
        </script>
</body>
</html>
<?php

require 'db.php'; // Incluye tu conexión a la base de datos

session_start(); // Asegúrate de iniciar la sesión

// Verificar si el id_usuario está disponible en la sesión
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    if (isset($_POST['add_combined'])) {
        // Obtener valores del formulario
        $tipo = $_POST['tipo'];
        $nombre_entidad = $_POST['Entidad_Nombre'];
        $rama_accion = $_POST['rama_accion'];
        $ruc = $_POST['ruc'];
        $email = $_POST['email'];
        $representante = $_POST['representante'];
        $fuente_financiacion = $_POST['fuente_financiacion'];
        $descripcion = $_POST['descripcion'];
        $tipo_cuenta = $_POST['tipo_cuenta'];
        $cuentas_bancarias = $_POST['cuentas_bancarias'];

        // Datos para redes sociales
        $tipo_red = $_POST['tipo_red'];
        $links = $_POST['links'];

        // Datos para teléfonos
        $telefono = $_POST['telefono'];

        // Datos para ubicación
        $pais = $_POST['pais'];
        $provincia = $_POST['provincia'];
        $canton = $_POST['canton'];
        $parroquia = $_POST['parroquia'];
        $altitud = $_POST['altitud'];
        $latitud = $_POST['latitud'];

        try {
            // Establece la conexión a la base de datos
            $conn = new mysqli('localhost', 'root', 'admin', 'ayudandoecuador1');

            // Verifica la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $conn->begin_transaction(); // Comenzar la transacción

            // 1. Insertar en la tabla Datos usando el id_usuario de la sesión
            $stmt = $conn->prepare("INSERT INTO Datos (id_usuario) VALUES (?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $id_dato = $conn->insert_id; // Obtener el id_dato recién insertado

            // 2. Insertar en la tabla Clasificacion
            $stmt = $conn->prepare("INSERT INTO Clasificacion (id_datos, tipo) VALUES (?, ?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("is", $id_dato, $tipo);
            $stmt->execute();

            // 3. Insertar en la tabla Entidad
            $stmt = $conn->prepare("INSERT INTO Entidad (id_dato, Entidad_Nombre, rama_accion, ruc, email, representante, fuente_financiacion, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("isssssss", $id_dato, $nombre_entidad, $rama_accion, $ruc, $email, $representante, $fuente_financiacion, $descripcion);
            $stmt->execute();

            // 4. Insertar en datos_adicionales
            $stmt = $conn->prepare("INSERT INTO datos_adicionales (id_dato, tipo_cuenta, cuentas_bancarias) VALUES (?, ?, ?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("iss", $id_dato, $tipo_cuenta, $cuentas_bancarias); // Asegúrate de que los tipos coincidan
            if (!$stmt->execute()) {
                die('Error en la inserción de datos adicionales: ' . $stmt->error);
            }


            // 5. Manejar la foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileData = file_get_contents($fileTmpPath);

                // Inserta la foto en la tabla fotos
                $stmt = $conn->prepare("INSERT INTO fotos (id_dato, foto_ruta) VALUES (?, ?)");
                if (!$stmt) {
                    die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
                }
                $stmt->bind_param("ib", $id_dato, $fileData); // "ib" significa entero y blob
                $stmt->execute();
            }

            // 6. Insertar en redes sociales
            $stmt = $conn->prepare("INSERT INTO redes_sociales (id_datos, tipo_red, links) VALUES (?, ?, ?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("iss", $id_dato, $tipo_red, $links);
            $stmt->execute();

            // 7. Insertar en teléfonos
            $stmt = $conn->prepare("INSERT INTO Telefonos (id_datos, telefono) VALUES (?, ?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("is", $id_dato, $telefono);
            $stmt->execute();

            // 8. Insertar en ubicación
            $stmt = $conn->prepare("INSERT INTO Ubicacion (id_dato, pais, provincia, canton, parroquia, altitud, latitud) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die('Error en la consulta SQL: ' . $conn->error); // Mostrar el error SQL si la preparación falla
            }
            $stmt->bind_param("issssss", $id_dato, $pais, $provincia, $canton, $parroquia, $altitud, $latitud);
            $stmt->execute();

            $conn->commit(); // Confirmar la transacción
            echo 'Agregado exitosamente.';
             // Si la inserción es exitosa, establece la sesión
    $_SESSION['agregado_exitoso'] = true;

    // Redirige a la misma página para que el cambio se refleje
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
        } catch (Exception $e) {
            $conn->rollback(); // Revertir la transacción en caso de error
            $_SESSION['agregado_exitoso'] = true;
            echo 'Error al agregar datos: ' . $e->getMessage();
        } finally {
            $conn->close(); // Cerrar la conexión
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CONSULT - Consultancy Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .content-section {
            display: none;
        }

        /* Ocultar secciones por defecto */
        .active-section {
            display: block;
        }
    </style>
    <script>
        function showSection(sectionId) {
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</head>

<div>
    <!-- Topbar Start -->
    <div class="container-fluid bg-secondary ps-5 pe-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center">
                    <a class="text-body py-2 pe-3 border-end" href=""><small>FAQs</small></a>
                    <a class="text-body py-2 px-3 border-end" href=""><small>Support</small></a>
                    <a class="text-body py-2 px-3 border-end" href=""><small>Privacy</small></a>
                    <a class="text-body py-2 px-3 border-end" href=""><small>Policy</small></a>
                    <a class="text-body py-2 ps-3" href=""><small>Career</small></a>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-end">
                <div class="position-relative d-inline-flex align-items-center bg-primary text-white top-shape px-5">
                    <div class="me-3 pe-3 border-end py-2">
                        <p class="m-0"><i class="fa fa-envelope-open me-2"></i>info@example.com</p>
                    </div>
                    <div class="py-2">
                        <p class="m-0"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
        <a href="index.html" class="navbar-brand p-0">
            <h1 class="m-0 text-uppercase text-primary"><i class="far fa-smile text-primary me-2"></i>consult</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 me-n3">
                <a href="index.php" class="nav-item nav-link">Inicio</a>
                <a href="entidades.php" class="nav-item nav-link">Entidades</a>

                <?php


                if (isset($_SESSION['id_usuario'])) {
                    // Si el usuario ha iniciado sesión, muestra solo Dashboard y Cerrar Sesión
                    echo '<a href="dashboard.php" class="nav-item nav-link">Dashboard</a>';
                    echo '<a href="logout.php" class="nav-item nav-link text-danger">Cerrar Sesión</a>';
                } else {
                    // Si el usuario no ha iniciado sesión, muestra el botón de Registro/Login
                    echo '<a href="service.php" class="nav-item nav-link">Registro/Login</a>';
                }
                ?>
            </div>
        </div>


    </nav>
    <!-- Navbar End -->





    <!-- Contact Start -->
    <div class="container-fluid bg-secondary px-0">
        <div class="row g-0">
            <div class="col-lg-12 py-8 px-5">


                <div class="container-fluid">
                    <div class="row">
                        <!-- Sidebar -->
                        <nav class="bg-light sidebar w-100">
                            <div class="position-sticky">
                                <h5 class="text-center mt-3">Dashboard</h5>
                                <ul class="nav nav-pills justify-content-center">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#" onclick="showSection('inicio')">
                                            <i class="fas fa-tachometer-alt"></i> Inicio
                                        </a>
                                    </li>
                                    <ul class="nav">
    <?php if (!isset($_SESSION['agregado_exitoso'])): ?>
        <!-- Botón de Configuración (solo se muestra si no se ha agregado exitosamente) -->
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showSection('configuracion')">
                <i class="fas fa-cogs"></i> Configuración
            </a>
        </li>
    <?php else: ?>
        <!-- Botón de Editar (se muestra después de un agregado exitoso) -->
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showSection('editar')">
                <i class="fas fa-edit"></i> Editar
            </a>
        </li>
    <?php endif; ?>
</ul>

                                </ul>
                            </div>
                        </nav>


                        <!-- Main Content -->
                        <main class="col-md-9 ml-sm-auto col-lg-6 px-md-5">
                            <!-- Inicio Section -->
                            <div id="inicio" class="content-section active-section">
                                <h2>Inicio</h2>
                                <div id="resultado" class="container-fluid bg-secondary">
                                    <?php
                                    // Verificar si el id_usuario está disponible en la sesión
                                    if (isset($_SESSION['id_usuario'])) {
                                        $id_usuario = $_SESSION['id_usuario'];

                                        // Conexión a la base de datos
                                        $conn = new mysqli('localhost', 'root', 'admin', 'ayudandoecuador1');

                                        // Verifica la conexión
                                        if ($conn->connect_error) {
                                            die("Conexión fallida: " . $conn->connect_error);
                                        }

                                        // Consulta para obtener los datos del usuario
                                        $sql = "
            SELECT 
                u.id_usuario,
                u.Nombre,
                u.Apellido,
                u.correo,
                u.contrasenia,
                d.id_datos,
                c.tipo AS clasificacion_tipo,
                da.tipo_cuenta,
                da.cuentas_bancarias,
                f.foto_ruta,
                rs.tipo_red AS redes_sociales_tipo,
                rs.links AS redes_sociales_links,
                t.telefono,
                uo.pais,
                uo.provincia,
                uo.canton,
                uo.parroquia,
                uo.altitud,
                uo.latitud,
                e.Entidad_Nombre,
                e.rama_accion,
                e.ruc,
                e.email AS entidad_email,
                e.representante,
                e.fuente_financiacion,
                e.descripcion AS entidad_descripcion
            FROM Usuarios u
            LEFT JOIN Datos d ON u.id_usuario = d.id_usuario
            LEFT JOIN Clasificacion c ON d.id_datos = c.id_datos
            LEFT JOIN datos_adicionales da ON d.id_datos = da.id_dato
            LEFT JOIN fotos f ON d.id_datos = f.id_dato
            LEFT JOIN redes_sociales rs ON d.id_datos = rs.id_datos
            LEFT JOIN Telefonos t ON d.id_datos = t.id_datos
            LEFT JOIN Ubicacion uo ON d.id_datos = uo.id_dato
            LEFT JOIN Entidad e ON d.id_datos = e.id_dato
            WHERE u.id_usuario = ?;
        ";

                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $id_usuario);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            // Estructura responsive para mostrar los datos en columnas
                                            echo "<div class='row gy-4'>";
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<div class='col-md-8 col-lg-8 data-item'>";

                                                // Información Personal
                                                if (!empty($row['Nombre']) || !empty($row['Apellido'])) {
                                                    echo "<h2>Información Personal</h2>";
                                                    echo "<p>Nombre: " . htmlspecialchars($row['Nombre']) . " " . htmlspecialchars($row['Apellido']) . "</p>";
                                                }
                                                if (!empty($row['correo'])) {
                                                    echo "<p>Correo: " . htmlspecialchars($row['correo']) . "</p>";
                                                }

                                                // Clasificación
                                                if (!empty($row['clasificacion_tipo'])) {
                                                    echo "<h2>Clasificación</h2>";
                                                    echo "<p>Tipo: " . htmlspecialchars($row['clasificacion_tipo']) . "</p>";
                                                }

                                                // Datos Adicionales
                                                if (!empty($row['tipo_cuenta']) || !empty($row['cuentas_bancarias'])) {
                                                    echo "<h2>Datos Adicionales</h2>";
                                                    echo "<p>Tipo de Cuenta: " . htmlspecialchars($row['tipo_cuenta']) . "</p>";
                                                    echo "<p>Cuentas Bancarias: " . htmlspecialchars($row['cuentas_bancarias']) . "</p>";
                                                }

                                                // Foto de Perfil
                                                if (!empty($row['foto_ruta'])) {
                                                    echo "<h2>Foto de Perfil</h2>";
                                                    echo "<img src='" . htmlspecialchars($row['foto_ruta']) . "' alt='Imagen de perfil' class='img-fluid' style='max-width: 200px; height: auto;'>";
                                                }

                                                // Redes Sociales
                                                if (!empty($row['redes_sociales_tipo']) || !empty($row['redes_sociales_links'])) {
                                                    echo "<h2>Redes Sociales</h2>";
                                                    echo "<p>Tipo de Red: " . htmlspecialchars($row['redes_sociales_tipo']) . "</p>";
                                                    echo "<p>Links: " . htmlspecialchars($row['redes_sociales_links']) . "</p>";
                                                }

                                                // Teléfonos
                                                if (!empty($row['telefono'])) {
                                                    echo "<h2>Contacto</h2>";
                                                    echo "<p>Teléfono: " . htmlspecialchars($row['telefono']) . "</p>";
                                                }

                                                // Ubicación
                                                if (!empty($row['pais']) || !empty($row['provincia']) || !empty($row['canton']) || !empty($row['parroquia'])) {
                                                    echo "<h2>Ubicación</h2>";
                                                    if (!empty($row['pais'])) {
                                                        echo "<p>País: " . htmlspecialchars($row['pais']) . "</p>";
                                                    }
                                                    if (!empty($row['provincia'])) {
                                                        echo "<p>Provincia: " . htmlspecialchars($row['provincia']) . "</p>";
                                                    }
                                                    if (!empty($row['canton'])) {
                                                        echo "<p>Cantón: " . htmlspecialchars($row['canton']) . "</p>";
                                                    }
                                                    if (!empty($row['parroquia'])) {
                                                        echo "<p>Parroquia: " . htmlspecialchars($row['parroquia']) . "</p>";
                                                    }
                                                    echo "<p>Altitud: " . htmlspecialchars($row['altitud']) . ", Latitud: " . htmlspecialchars($row['latitud']) . "</p>";
                                                }

                                                // Entidad
                                                if (!empty($row['Entidad_Nombre']) || !empty($row['rama_accion'])) {
                                                    echo "<h2>Entidad</h2>";
                                                    echo "<p>Nombre de Entidad: " . htmlspecialchars($row['Entidad_Nombre']) . "</p>";
                                                    echo "<p>Rama de Acción: " . htmlspecialchars($row['rama_accion']) . "</p>";
                                                    echo "<p>RUC: " . htmlspecialchars($row['ruc']) . "</p>";
                                                    echo "<p>Email: " . htmlspecialchars($row['entidad_email']) . "</p>";
                                                    echo "<p>Representante: " . htmlspecialchars($row['representante']) . "</p>";
                                                    echo "<p>Fuente de Financiación: " . htmlspecialchars($row['fuente_financiacion']) . "</p>";
                                                    echo "<p>Descripción: " . htmlspecialchars($row['entidad_descripcion']) . "</p>";
                                                }

                                                echo "</div>"; // Fin del col
                                            }
                                            echo "</div>"; // Fin de la fila
                                        } else {
                                            echo "<p>No hay datos disponibles.</p>";
                                        }

                                        // Cerrar la conexión
                                        $stmt->close();
                                        $conn->close();
                                    } else {
                                        echo "<p>Error: Usuario no autenticado.</p>";
                                    }
                                    ?>
                                </div> <!-- Fin de contenedor -->


                            </div>

                        </main>


                        <!-- Configuración Section -->


                        <div id="configuracion" class="content-section">
                            <h2>Configuración</h2>

                            <form method="post" enctype="multipart/form-data">
                                <h1>Añadir Clasificación y Entidad</h1>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tipo"><i class="bi bi-tag"></i> Tipo de Clasificación:</label>
                                            <select class="form-control" id="tipo" name="tipo" required>
                                                <option value="" disabled selected>Seleccione un tipo de entidad</option>
                                                <option value="Organización No Gubernamental (ONG)">Organización No Gubernamental (ONG)</option>
                                                <option value="Cruz Roja">Cruz Roja</option>
                                                <option value="Fondo de las Naciones Unidas para la Infancia (UNICEF)">Fondo de las Naciones Unidas para la Infancia (UNICEF)</option>
                                                <option value="Organización Mundial de la Salud (OMS)">Organización Mundial de la Salud (OMS)</option>
                                                <option value="Caritas">Caritas</option>
                                                <option value="Albergue de Animales">Albergue de Animales</option>
                                                <option value="Fundación de Ayuda Humanitaria">Fundación de Ayuda Humanitaria</option>
                                                <option value="Organización de Voluntarios">Organización de Voluntarios</option>
                                                <option value="Cuerpo de Paz">Cuerpo de Paz</option>
                                                <option value="Fondo de Emergencia">Fondo de Emergencia</option>
                                                <option value="Protección Civil">Protección Civil</option>
                                                <option value="Asociación de Desarrollo Comunitario">Asociación de Desarrollo Comunitario</option>
                                                <option value="Banco de Alimentos">Banco de Alimentos</option>
                                                <option value="Asociación Internacional de Rescate">Asociación Internacional de Rescate</option>
                                                <option value="Save the Children">Save the Children</option>
                                                <option value="Organización Internacional para las Migraciones (OIM)">Organización Internacional para las Migraciones (OIM)</option>
                                                <option value="Médicos Sin Fronteras">Médicos Sin Fronteras</option>
                                                <option value="Fundación Red Cross">Fundación Red Cross</option>
                                                <option value="Fondo Global de Lucha contra el SIDA, la Tuberculosis y la Malaria">Fondo Global de Lucha contra el SIDA, la Tuberculosis y la Malaria</option>
                                                <option value="GlobalGiving">GlobalGiving</option>
                                                <option value="Asociación de Ayuda Humanitaria a Desastres">Asociación de Ayuda Humanitaria a Desastres</option>
                                                <option value="Fundación de Animales Rescatados">Fundación de Animales Rescatados</option>
                                                <option value="Asociación de Protección Ambiental">Asociación de Protección Ambiental</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="Entidad_Nombre"><i class="bi bi-building"></i> Nombre de la Entidad:</label>
                                            <input type="text" class="form-control" id="Entidad_Nombre" name="Entidad_Nombre" required>
                                        </div>


                                        <div class="form-group">
                                            <label for="rama_accion"><i class="bi bi-gear"></i> Rama de Acción:</label>
                                            <select class="form-control" id="rama_accion" name="rama_accion" required>
                                                <option value="" disabled selected>Seleccione una rama de acción</option>
                                                <option value="Salud">Salud</option>
                                                <option value="Educación">Educación</option>
                                                <option value="Alimentación">Alimentación</option>
                                                <option value="Refugio">Refugio</option>
                                                <option value="Desarrollo Comunitario">Desarrollo Comunitario</option>
                                                <option value="Protección Infantil">Protección Infantil</option>
                                                <option value="Emergencias Humanitarias">Emergencias Humanitarias</option>
                                                <option value="Protección Ambiental">Protección Ambiental</option>
                                                <option value="Rehabilitación y Recuperación">Rehabilitación y Recuperación</option>
                                                <option value="Ayuda a Desastres">Ayuda a Desastres</option>
                                                <option value="Asistencia a Refugiados">Asistencia a Refugiados</option>
                                                <option value="Derechos Humanos">Derechos Humanos</option>
                                                <option value="Sostenibilidad">Sostenibilidad</option>
                                                <option value="Desarrollo Económico">Desarrollo Económico</option>
                                                <option value="Empoderamiento de Género">Empoderamiento de Género</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ruc"><i class="bi bi-file-earmark-text"></i> RUC:</label>
                                            <input type="text" class="form-control" id="ruc" name="ruc" required pattern="^[0-9]{11}$" title="El RUC debe tener 11 dígitos">
                                            <small class="form-text text-muted">Introduce un RUC válido de 11 dígitos.</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="email"><i class="bi bi-envelope"></i> Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="representante"><i class="bi bi-person-circle"></i> Representante:</label>
                                            <input type="text" class="form-control" id="representante" name="representante" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="fuente_financiacion"><i class="bi bi-cash"></i> Fuente de Financiación:</label>
                                            <select class="form-control" id="fuente_financiacion" name="fuente_financiacion" required>
                                                <option value="" disabled selected>Seleccione una fuente de financiación</option>
                                                <option value="Donaciones">Donaciones</option>
                                                <option value="Subvenciones">Subvenciones</option>
                                                <option value="Patrocinios">Patrocinios</option>
                                                <option value="Fondos Gubernamentales">Fondos Gubernamentales</option>
                                                <option value="Recursos Propios">Recursos Propios</option>
                                                <option value="Crowdfunding">Crowdfunding</option>
                                                <option value="Financiamiento Internacional">Financiamiento Internacional</option>
                                                <option value="Inversiones Privadas">Inversiones Privadas</option>
                                                <option value="Aportaciones de Socios">Aportaciones de Socios</option>
                                                <option value="Contratos de Servicios">Contratos de Servicios</option>
                                                <option value="Venta de Productos/Servicios">Venta de Productos/Servicios</option>
                                                <option value="Otros">Otros</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="descripcion"><i class="bi bi-file-earmark-text"></i> Descripción:</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                                            <small id="wordCountMessage" class="form-text text-danger" style="display:none;">La descripción debe contener al menos 200 palabras.</small>
                                        </div>

                                        <script>
                                            // Función para contar palabras
                                            function countWords() {
                                                const textArea = document.getElementById('descripcion');
                                                const wordCountMessage = document.getElementById('wordCountMessage');
                                                const words = textArea.value.trim().split(/\s+/);
                                                const wordCount = words.filter(word => word.length > 0).length;

                                                // Verificar si el conteo de palabras es menor a 200
                                                if (wordCount < 20) {
                                                    wordCountMessage.style.display = 'block'; // Muestra el mensaje
                                                    return false; // Previene el envío del formulario
                                                } else {
                                                    wordCountMessage.style.display = 'none'; // Oculta el mensaje
                                                    return true; // Permite el envío del formulario
                                                }
                                            }

                                            // Agregar evento de validación al formulario
                                            document.querySelector('form').addEventListener('submit', function(event) {
                                                if (!countWords()) {
                                                    event.preventDefault(); // Previene el envío si no se cumplen las condiciones
                                                }
                                            });
                                        </script>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tipo_cuenta"><i class="bi bi-credit-card"></i> Tipo de Cuenta:</label>
                                            <select class="form-control" id="tipo_cuenta" name="tipo_cuenta" required>
                                                <option value="">Seleccione un banco o cooperativa</option>
                                                <option value="Banco Pichincha">Banco Pichincha</option>
                                                <option value="Banco del Pacífico">Banco del Pacífico</option>
                                                <option value="Banco Guayaquil">Banco Guayaquil</option>
                                                <option value="Produbanco">Produbanco</option>
                                                <option value="Diners Club del Ecuador">Diners Club del Ecuador</option>
                                                <option value="Banco Internacional">Banco Internacional</option>
                                                <option value="Banco Bolivariano">Banco Bolivariano</option>
                                                <option value="Cooperativa de Ahorro y Crédito Cacpe Loja">Cooperativa Cacpe Loja</option>
                                                <option value="Cooperativa de Ahorro y Crédito Santa Clara">Cooperativa Santa Clara</option>
                                                <option value="Cooperativa de Ahorro y Crédito Jardín Azuayo">Cooperativa Jardín Azuayo</option>
                                                <option value="Cooperativa de Ahorro y Crédito Chibuleo">Cooperativa Chibuleo</option>
                                                <option value="Cooperativa de Ahorro y Crédito La Maná">Cooperativa La Maná</option>
                                                <option value="Cooperativa de Ahorro y Crédito 29 de Octubre">Cooperativa 29 de Octubre</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="cuentas_bancarias"><i class="bi bi-bank"></i> Cuentas Bancarias:</label>
                                            <input type="number" class="form-control" id="cuentas_bancarias" name="cuentas_bancarias" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="foto"><i class="bi bi-image"></i> Cargar Foto:</label>
                                            <input type="file" name="foto" id="foto" accept="image/*">

                                        </div>

                                        <div class="form-group">
                                            <label for="tipo_red"><i class="bi bi-share"></i> Tipo de Red Social:</label>
                                            <select class="form-control" id="tipo_red" name="tipo_red" required>
                                                <option value="">Seleccione una red social</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Twitter">Twitter</option>
                                                <option value="LinkedIn">LinkedIn</option>
                                                <option value="TikTok">TikTok</option>
                                                <option value="YouTube">YouTube</option>
                                                <option value="WhatsApp">WhatsApp</option>
                                                <option value="Snapchat">Snapchat</option>
                                                <option value="Telegram">Telegram</option>
                                                <option value="Pinterest">Pinterest</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="links"><i class="bi bi-link"></i> Enlace de Red Social:</label>
                                            <input type="url" class="form-control" id="links" name="links" placeholder="https://ejemplo.com" required pattern="https?://.+">
                                            <small class="form-text text-muted">Por favor, ingrese un enlace válido (ejemplo: https://www.facebook.com/usuario).</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="telefono"><i class="bi bi-telephone"></i> Teléfono:</label>
                                            <input type="number" class="form-control" id="telefono" name="telefono" required>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pais"><i class="bi bi-geo-alt"></i> País:</label>
                                            <input type="text" class="form-control" id="pais" name="pais" value="Ecuador" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="provincia"><i class="bi bi-map"></i> Provincia:</label>
                                            <select class="form-control" id="provincia" name="provincia" required onchange="cargarCantones()">
                                                <option value="">Selecciona una provincia</option>
                                                <!-- Las opciones se llenarán dinámicamente -->
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="canton"><i class="bi bi-map"></i> Cantón:</label>
                                            <select class="form-control" id="canton" name="canton" required onchange="cargarParroquias()">
                                                <option value="">Selecciona un cantón</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="parroquia"><i class="bi bi-map"></i> Parroquia:</label>
                                            <select class="form-control" id="parroquia" name="parroquia" required>
                                                <option value="">Selecciona una parroquia</option>
                                            </select>
                                        </div>

                                        <script>
                                            let data = {};

                                            // Cargar los datos del archivo JSON
                                            fetch('provincias.json')
                                                .then(response => response.json())
                                                .then(json => {
                                                    data = json;
                                                    cargarProvincias();
                                                })
                                                .catch(error => console.error('Error al cargar el JSON:', error));

                                            function cargarProvincias() {
                                                const provinciaSelect = document.getElementById('provincia');
                                                for (const id in data) {
                                                    const option = document.createElement('option');
                                                    option.value = data[id].provincia; // Cambiar para guardar el nombre de la provincia
                                                    option.textContent = data[id].provincia;
                                                    provinciaSelect.appendChild(option);
                                                }
                                            }

                                            function cargarCantones() {
                                                const provinciaSelect = document.getElementById('provincia');
                                                const cantonSelect = document.getElementById('canton');
                                                const parroquiaSelect = document.getElementById('parroquia');

                                                const provinciaId = provinciaSelect.selectedIndex > 0 ? Object.keys(data)[provinciaSelect.selectedIndex - 1] : null;

                                                // Limpiar cantones y parroquias
                                                cantonSelect.innerHTML = '<option value="">Selecciona un cantón</option>';
                                                parroquiaSelect.innerHTML = '<option value="">Selecciona una parroquia</option>';

                                                if (provinciaId) {
                                                    const cantones = data[provinciaId].cantones;
                                                    for (const cantonId in cantones) {
                                                        const canton = cantones[cantonId];
                                                        const option = document.createElement('option');
                                                        option.value = canton.canton; // Cambiar para guardar el nombre del cantón
                                                        option.textContent = canton.canton;
                                                        cantonSelect.appendChild(option);
                                                    }
                                                }
                                            }

                                            function cargarParroquias() {
                                                const cantonSelect = document.getElementById('canton');
                                                const parroquiaSelect = document.getElementById('parroquia');

                                                const provinciaId = document.getElementById('provincia').selectedIndex > 0 ? Object.keys(data)[document.getElementById('provincia').selectedIndex - 1] : null;
                                                const cantonId = cantonSelect.selectedIndex > 0 ? Object.keys(data[provinciaId].cantones)[cantonSelect.selectedIndex - 1] : null;

                                                // Limpiar parroquias
                                                parroquiaSelect.innerHTML = '<option value="">Selecciona una parroquia</option>';

                                                if (cantonId) {
                                                    const parroquias = data[provinciaId].cantones[cantonId].parroquias;
                                                    for (const parroquiaId in parroquias) {
                                                        const option = document.createElement('option');
                                                        option.value = parroquias[parroquiaId]; // Cambiar para guardar el nombre de la parroquia
                                                        option.textContent = parroquias[parroquiaId];
                                                        parroquiaSelect.appendChild(option);
                                                    }
                                                }
                                            }
                                        </script>


                                        <div class="form-group">
                                            <label for="altitud"><i class="bi bi-geo-alt"></i> Altitud :</label>
                                            <input type="number" step="any" class="form-control" id="altitud" name="altitud" placeholder="Ejemplo: -22.53400" required>
                                            <small class="form-text text-muted">Ingresa la altitud Ejemplo: -2.9000 (coordenadas geográficas)).</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="latitud"><i class="bi bi-geo-alt"></i> Latitud:</label>
                                            <input type="number" step="any" class="form-control" id="latitud" name="latitud" placeholder="Ejemplo: -2.9000" required>
                                            <small class="form-text text-muted">Ingresa la latitud del lugar. Ejemplo: -2.9000 (coordenadas geográficas).</small>
                                        </div>


                                        <button type="submit" name="add_combined" class="btn btn-primary">Agregar</button>
                                    </div>
                                </div>

                            </form>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</div>

</div>
</div>

<!-- JavaScript para alternar secciones y guardar datos -->
<script>
    function showSection(sectionId) {
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active-section');
        });
        document.getElementById(sectionId).classList.add('active-section');
    }

    function guardarDatos(event) {
        event.preventDefault(); // Evitar el envío del formulario

        // Obtener los valores del formulario
        const nombre = document.getElementById('nombre').value;
        const email = document.getElementById('email').value;

        // Mostrar el resultado en la sección de Inicio
        document.getElementById('resultado').innerHTML = `<p><strong>Nombre:</strong> ${nombre}</p><p><strong>Email:</strong> ${email}</p>`;

        // Alternar a la sección de Inicio después de guardar los datos
        showSection('inicio');
    }
</script>
<script>
    document.getElementById('miFormulario').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        const formData = new FormData(this); // Obtener los datos del formulario

        fetch('dashboard.php', { // Cambia esto a la ruta de tu script PHP
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Convertir la respuesta a JSON
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message); // Mostrar mensaje de éxito
                    document.querySelector('.nav-item a[href="#"][onclick="showSection(\'configuracion\')"]').parentElement.style.display = 'none'; // Ocultar botón
                } else {
                    alert('Error: ' + data.message); // Mostrar mensaje de error
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
                alert('Error al enviar el formulario. Intenta nuevamente.');
            });
    });
</script>


<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>



</body>

</div>
</div>



</div>
</div>

<!-- Contact End -->


<!-- Footer Start -->

<div class="container-fluid bg-dark text-secondary p-5">
    <div class="row g-5">
        <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Quick Links</h3>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog Post</a>
                <a class="text-secondary" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Popular Links</h3>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog Post</a>
                <a class="text-secondary" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Get In Touch</h3>
            <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i>123 Street, New York, USA</p>
            <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>info@example.com</p>
            <p class="mb-0"><i class="bi bi-telephone text-primary me-2"></i>+012 345 67890</p>
        </div>
        <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Follow Us</h3>
            <div class="d-flex">
                <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i class="fab fa-instagram fw-normal"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-dark text-secondary text-center border-top py-4 px-5" style="border-color: rgba(256, 256, 256, .1) !important;">
    <p class="m-0">&copy; <a class="text-secondary border-bottom" href="#">Your Site Name</a>. All Rights Reserved. Designed by <a class="text-secondary border-bottom" href="https://htmlcodex.com">HTML Codex</a></p>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>

</html>
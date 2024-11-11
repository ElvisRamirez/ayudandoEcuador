<?php
session_start();


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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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
                
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', 'admin', 'ayudandoecuador1');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID de la entidad desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "
    SELECT 
        u.Nombre AS usuario_nombre,
        u.Apellido AS usuario_apellido,
        u.correo AS usuario_correo,
        
        e.Entidad_Nombre,
        e.rama_accion,
        e.descripcion AS entidad_descripcion,
        
        f.foto_ruta,
        c.tipo AS clasificacion_tipo,
        
        uo.provincia,
        uo.canton,
        uo.parroquia,
        
        t.telefono,
        
        r.tipo_red,
        r.links AS red_links,
        
        da.tipo_cuenta,
        da.cuentas_bancarias
    FROM Entidad e
    LEFT JOIN Datos d ON e.id_dato = d.id_datos
    LEFT JOIN Usuarios u ON d.id_usuario = u.id_usuario
    LEFT JOIN fotos f ON e.id_dato = f.id_dato
    LEFT JOIN Clasificacion c ON e.id_dato = c.id_datos
    LEFT JOIN Ubicacion uo ON e.id_dato = uo.id_dato
    LEFT JOIN Telefonos t ON e.id_dato = t.id_datos
    LEFT JOIN redes_sociales r ON e.id_dato = r.id_datos
    LEFT JOIN datos_adicionales da ON e.id_dato = da.id_dato
    WHERE e.id_entidad = ?"; // Filtro para obtener la entidad por su ID

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Asignar parámetros a la consulta
    $stmt->bind_param("i", $id); // 'i' para entero (ID de la entidad)

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mostrar los detalles de la entidad seleccionada
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='container py-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                            <h1 class='display-4 text-center'>" . htmlspecialchars($row['Entidad_Nombre']) . "</h1>
                            <h3 class='text-center text-muted'>" . htmlspecialchars($row['rama_accion']) . "</h3>
                            <hr>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-lg-6 col-md-12'>
                            <h4>Descripción:</h4>
                            <p>" . htmlspecialchars($row['entidad_descripcion']) . "</p>
                        </div>
                        <div class='col-lg-6 col-md-12'>
                            <h4>Clasificación:</h4>
                            <p>" . htmlspecialchars($row['clasificacion_tipo']) . "</p>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            <h4>Ubicación:</h4>
                            <p><strong>Provincia:</strong> " . htmlspecialchars($row['provincia']) . "</p>
                            <p><strong>Canton:</strong> " . htmlspecialchars($row['canton']) . "</p>
                            <p><strong>Parroquia:</strong> " . htmlspecialchars($row['parroquia']) . "</p>
                        </div>
                        <div class='col-md-6'>
                            <h4>Fotos Relacionadas:</h4>";
                            // Si hay fotos relacionadas con la entidad, las mostramos
                            if (!empty($row['foto_ruta'])) {
                                echo "<img src='" . htmlspecialchars($row['foto_ruta']) . "' alt='Imagen de la entidad' class='img-fluid mb-3'>";
                            } else {
                                echo "<p>No se encontraron fotos para esta entidad.</p>";
                            }
                        echo "</div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            <h4>Teléfonos:</h4>";
                            // Mostrar teléfonos relacionados con la entidad
                            if (!empty($row['telefono'])) {
                                echo "<p>" . htmlspecialchars($row['telefono']) . "</p>";
                            } else {
                                echo "<p>No se encontraron teléfonos para esta entidad.</p>";
                            }
                        echo "</div>
                        <div class='col-md-6'>
                            <h4>Redes Sociales:</h4>";
                            // Mostrar redes sociales relacionadas con la entidad
                            if (!empty($row['tipo_red']) && !empty($row['red_links'])) {
                                echo "<p><strong>" . htmlspecialchars($row['tipo_red']) . ":</strong> <a href='" . htmlspecialchars($row['red_links']) . "' target='_blank'>" . htmlspecialchars($row['red_links']) . "</a></p>";
                            } else {
                                echo "<p>No se encontraron redes sociales para esta entidad.</p>";
                            }
                        echo "</div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            <h4>Datos Adicionales:</h4>";
                            // Mostrar datos adicionales si están presentes
                            if (!empty($row['tipo_cuenta']) && !empty($row['cuentas_bancarias'])) {
                                echo "<p><strong>Tipo de Cuenta:</strong> " . htmlspecialchars($row['tipo_cuenta']) . "</p>
                                      <p><strong>Cuentas Bancarias:</strong> " . htmlspecialchars($row['cuentas_bancarias']) . "</p>";
                            } else {
                                echo "<p>No se encontraron datos adicionales para esta entidad.</p>";
                            }
                        echo "</div>
                    </div>
                    <hr>
                </div>
            ";
        }
    } else {
        echo "<p>No se encontraron detalles para esta entidad.</p>";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>

   

    <!-- Footer Start -->
    <div class="container-fluid bg-primary text-secondary p-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-5 mb-4">Stay Update!!!</h1>
                <form class="mx-auto" style="max-width: 600px;">
                    <div class="input-group">
                        <input type="text" class="form-control border-white p-3" placeholder="Your Email">
                        <button class="btn btn-dark px-4">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

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
    <style>
        /* Contenedor para las tarjetas */
/* Contenedor para las tarjetas */
.container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; /* Espacio entre columnas (y filas) */
}

/* Asegurarse de que las tarjetas tengan el mismo tamaño */
.card {
    width: 100%; /* Para ocupar todo el ancho dentro de una columna */
    height: 100%; /* Para que las tarjetas sean uniformes en altura */
    max-width: 300px; /* Limitar el ancho máximo de las tarjetas */
    margin-bottom: 20px;
    border: 1px solid #ddd; /* Bordes suaves */
    border-radius: 10px; /* Bordes redondeados */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Sombra ligera */
}

/* Hacer que las tarjetas se ajusten con el grid */
.col-md-4 {
    flex: 1 0 30%; /* Esto hará que cada tarjeta ocupe aproximadamente un 30% del ancho de su contenedor */
    max-width: 300px; /* Limitar el tamaño máximo de la columna */
}

/* Agregar barra de desplazamiento a la descripción */
.descripcion {
    max-height: 100px; /* Altura máxima para la descripción */
    overflow-y: auto; /* Habilitar desplazamiento vertical si es necesario */
    padding-right: 5px; /* Espacio extra para la barra de desplazamiento */
    text-overflow: ellipsis; /* Agregar puntos suspensivos si es necesario */
}

/* Estilos adicionales opcionales */
.card-img-top {
    height: 200px; /* Limitar la altura de la imagen */
    object-fit: cover; /* Asegura que la imagen se recorte de manera uniforme */
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
/* Ajustando espacio con márgenes en las columnas */
.col-md-4 {
    flex: 1 0 30%;
    max-width: 300px;
    margin-right: 15px; /* Espacio entre columnas */
    margin-bottom: 20px; /* Espacio entre filas */
}

/* Para quitar el margen en la última columna de cada fila */
.col-md-4:last-child {
    margin-right: 0;
}


    </style>
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

</div>
</nav>
<!-- Navbar End -->





<!-- About Start -->
<div class="container-fluid bg-secondary p-0">
    <div class="row g-0">
        
    <?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', 'admin', 'ayudandoecuador1');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener todas las entidades
$sql = "
    SELECT 
        e.id_dato,
        e.Entidad_Nombre,
        e.rama_accion,
        e.descripcion AS entidad_descripcion,
        f.foto_ruta,
        c.tipo AS clasificacion_tipo
    FROM Entidad e
    LEFT JOIN fotos f ON e.id_dato = f.id_dato
    LEFT JOIN Clasificacion c ON e.id_dato = c.id_datos;
";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mostrar todas las entidades
    while ($row = $result->fetch_assoc()) {
        echo "
            <div class='col-md-4'>
                <div class='card'>
                    <img src='" . htmlspecialchars($row['foto_ruta']) . "' class='card-img-top' alt='Imagen de la entidad'>
                    <div class='card-body'>
                        <h5 class='card-title'>" . htmlspecialchars($row['Entidad_Nombre']) . "</h5>
                        <p class='card-text'><strong>Clasificación:</strong> " . htmlspecialchars($row['clasificacion_tipo']) . "</p>
                        <p class='card-text descripcion'><strong>Descripción:</strong> " . htmlspecialchars($row['entidad_descripcion']) . "</p>
                        <p class='card-text'><strong>Rama de Acción:</strong> " . htmlspecialchars($row['rama_accion']) . "</p>
                        <a href='ver_entidad.php?id=" . $row['id_dato'] . "' class='btn btn-primary'>Ver</a>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p>No se encontraron detalles para las entidades.</p>";
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>





    </div>
</div>

<!-- About End -->


<!-- Team Start -->
<div class="container-fluid py-6 px-5">
    <div class="text-center mx-auto mb-5" style="max-width: 600px;">
        <h1 class="display-5 mb-0">Our Team Members</h1>
        <hr class="w-25 mx-auto bg-primary">
    </div>
    <div class="row g-5">
        <div class="col-lg-4">
            <div class="team-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/team-1.jpg" alt="">
                <div class="team-text w-100 position-absolute top-50 text-center bg-primary p-4">
                    <h3 class="text-white">Full Name</h3>
                    <p class="text-white text-uppercase mb-0">Designation</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="team-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/team-2.jpg" alt="">
                <div class="team-text w-100 position-absolute top-50 text-center bg-primary p-4">
                    <h3 class="text-white">Full Name</h3>
                    <p class="text-white text-uppercase mb-0">Designation</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="team-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/team-3.jpg" alt="">
                <div class="team-text w-100 position-absolute top-50 text-center bg-primary p-4">
                    <h3 class="text-white">Full Name</h3>
                    <p class="text-white text-uppercase mb-0">Designation</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Team End -->


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
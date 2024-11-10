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
        e.Entidad_Nombre,
        e.rama_accion,
        e.descripcion AS entidad_descripcion,
        f.foto_ruta,
        c.tipo AS clasificacion_tipo,
        u.provincia,
        u.canton,
        u.parroquia,
        t.telefono,
        r.tipo_red,
        r.links AS red_links,
        da.tipo_cuenta,
        da.cuentas_bancarias
    FROM Entidad e
    LEFT JOIN fotos f ON e.id_dato = f.id_dato
    LEFT JOIN Clasificacion c ON e.id_dato = c.id_datos
    LEFT JOIN Ubicacion u ON e.id_dato = u.id_dato
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

<?php
session_start();
require "../config.php";

// Verifica si se recibió un ID en la URL
if (isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);

    // Consulta para obtener los detalles del producto
    $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si se encontró el producto
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no proporcionado.";
    exit();
}

require "carrito_cont.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>


<body>

<style>
    .producto-imagen-grande {
    width: 100%;
    height: auto;
    max-height: 450px; /* Ajusta según tu preferencia */
    object-fit: cover;
}


        /* Estilos para el menú desplegable */
.dropdown {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0.5rem 0;
    min-width: 150px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.dropdown-menu .dropdown-link {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background 0.3s ease;
}

.dropdown-menu .dropdown-link:hover {
    background: #ff69b4;
    color: #fff;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

</style>

<header>
<nav class="navbar">
    <div class="logo">
        
            <img class="logo_img" src="/img/logo.png" alt="Vainiya! Bakery">
            <a href="inicio.php" style="text-decoration: none; color: inherit;">
            <h1>Vainiya! Bakery</h1>
        </a>
    </div>
    <ul id="nav-menu">
        <li><a href="sobre-nosotros.php" class="nav-link">Sobre Nosotros</a></li>
        <li><a href="disenar_pastel.php" class="nav-link btn-requiere-sesion">Personalizar Pastel</a></li>
        <li class="logged-in"><a href="#pedidos" class="nav-link">Pedidos</a></li>

        <!-- Menú desplegable para Perfil -->
        <li class="dropdown logged-in">
            <a class="nav-link">Perfil <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="perfil.php" class="dropdown-link">Información</a></li>
                <li><a href="logout.php" class="dropdown-link">Cerrar Sesión</a></li>
            </ul>
        </li>

        <!-- Ícono del carrito con cantidad -->
        <li>
            <a href="carrito.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <!-- Muestra la cantidad de productos en el carrito -->
                <?php echo $carrito_items; ?>
            </a>
        </li>

    </ul>
</nav>

</header>
    <main class="container my-5">
        <div class="row g-4">
            <div class="col-md-6">
                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="img-fluid rounded shadow producto-imagen-grande">
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <h2 class="product-title mb-3"><?php echo $producto['nombre']; ?></h2>
                <p class="product-description mb-4"><?php echo $producto['descripcion']; ?></p>
                <div class="price-section mb-4">
                    <span class="current-price">$<?php echo number_format($producto['precio'], 2); ?></span>
                </div>
                <div class="d-grid gap-2">
                    <form method="post" action="procesar_accion.php">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1" required>
                        </div>
                        <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($nombre_producto); ?>">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                        <input type="hidden" name="precio_producto" value="<?php echo $producto['precio']; ?>">

                        <!-- Botones con diferentes acciones -->
                        <button type="submit" name="accion" value="carrito" class="btn btn-primary btn-lg">Añadir al Carrito 🛒</button>
                        <button type="submit" name="accion" value="comprar_ahora" class="btn btn-primary btn-lg">Comprar Ahora</button>
                    </form>
                </div>


            </div>
        </div>
    </main>

    <footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>Vainiya! Bakery</h3>
            <p>Endulzando momentos especiales con nuestras deliciosas creaciones.</p>
        </div>
        <div class="footer-section">
            <h3>Síguenos</h3>
            <div class="social-links">
                <a href="https://www.instagram.com/vainiyabakery/profilecard/?igsh=MW1keWZwcHM2dTlodw%3D%3D"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@vainiyabakery?_t=8k4gBw9kKnt&_r=1"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
        <div class="footer-section">
            <h3>Contacto</h3>
            <p><i class="fas fa-map-marker-alt"></i> Tulancingo de Bravo; Hgo.</p>
            <p><i class="fas fa-phone"></i> Teléfono: (52) 775-105-96-89</p>
            <p><i class="fas fa-envelope"></i> Email: info@vainiya.com</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Vainiya! Bakery. Todos los derechos reservados.</p>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

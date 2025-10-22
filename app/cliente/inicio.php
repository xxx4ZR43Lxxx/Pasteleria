<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");  // Redirige al login si no está autenticado
    exit();
}
require "../config.php";
require "carrito_cont.php";
$sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vainiya! Bakery</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
</head>
<body>
    
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
        <li class="logged-in"><a href="pedidos.php" class="nav-link">Pedidos</a></li>

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

<main>
    <section class="hero">
        <h2>¡Bienvenidos a Vainiya!</h2>
        <p>Descubre nuestras deliciosas creaciones artesanales</p>
        <button class="btn-primary" onclick="window.location='disenar_pastel.php'">Personalizar tu Pastel</button>
    </section>

    <section class="productos">
        <h2>Nuestros Productos Destacados</h2>
        <div class="productos-grid">
            <?php
            // Generar dinámicamente las tarjetas de producto
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="producto-card">';
                    echo '<a href="detalle_producto.php?id=' . $row["id"] . '" style="text-decoration:none; color:black">';
                    echo '<img class="producto-imagen-grande" src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
                    echo '<div class="producto-info">';
                    echo '<h3>' . $row["nombre"] . '</h3>';
                    echo '<p class="producto-descripcion">' . $row["descripcion"] . '</p>';
                    echo '<span class="producto-precio">$' . $row["precio"] . '</span>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No hay productos disponibles en este momento.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>
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

<script src="/js/script.js"></script>
</body>
</html>


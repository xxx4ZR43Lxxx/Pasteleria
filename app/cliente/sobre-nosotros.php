<?php
session_start();
require "../config.php";
require "carrito_cont.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Dulces Delicias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
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
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <h1 class="display-4 mb-4">Sobre Nosotros</h1>
                <p class="lead mb-4">Somos una pastelería artesanal con más de una década de experiencia, especializada en crear momentos dulces inolvidables desde 2021.</p>
                <p class="mb-4">Nuestra pasión es hornear los pasteles más deliciosos y creativos para todas sus celebraciones especiales. Cada creación es elaborada con ingredientes premium y decorada con amor y dedicación por nuestros expertos pasteleros.</p>
                <div class="row g-4 mt-3">
                    <div class="col-6">
                        <div class="achievement-card">
                            <h3 class="h2 mb-2">3+</h3>
                            <p class="mb-0">Años Endulzando Vidas</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="achievement-card">
                            <h3 class="h2 mb-2">10k+</h3>
                            <p class="mb-0">Pasteles Horneados</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587" alt="Nuestros Pasteles" class="img-fluid rounded shadow-lg about-image">
            </div>
        </div>

        <div class="row g-4 mt-5">
            <h2 class="text-center mb-4">Nuestros Valores</h2>
            <div class="col-md-4">
                <div class="value-card text-center p-4">
                    <i class="bi bi-star-fill value-icon mb-3"></i>
                    <h3 class="h4">Calidad</h3>
                    <p>Utilizamos los mejores ingredientes para crear pasteles excepcionales.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card text-center p-4">
                    <i class="bi bi-heart-fill value-icon mb-3"></i>
                    <h3 class="h4">Pasión</h3>
                    <p>Cada pastel es elaborado con amor y dedicación artesanal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card text-center p-4">
                    <i class="bi bi-gem-fill value-icon mb-3"></i>
                    <h3 class="h4">Creatividad</h3>
                    <p>Diseños únicos para hacer de cada ocasión algo especial.</p>
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
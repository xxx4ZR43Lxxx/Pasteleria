<?php
session_start();
require "../config.php";
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
</head>
<body>
<header>
    <nav>
      <div class="logo">
        <img class="logo_img" src="/img/logo.png" alt="Vainiya! Bakery">
        <h1>Vainiya! Bakery</h1>
      </div>
      <ul>
        <li><a href="inicio.php">Inicio</a></li>
        <li><a href="sobre-nosotros.php">Sobre Nosotros</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="productos.php">Productos</a></li>
        <li><a href="pedidos.php">Pedidos</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
      </ul>
    </nav>
</header>


    <main>
        <section class="hero">
            <h2>¡Bienvenidos a Vainiya!</h2>
            <p>Descubre nuestras deliciosas creaciones artesanales</p>
            <button class="btn-primary btn-requiere-sesion">Personalizar tu Pastel</button>
        </section>

        <section class="productos">
        <h2>Nuestros Productos Destacados</h2>
        <div class="productos-grid">
            <?php
            // Generar dinámicamente las tarjetas de producto
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="producto-card">';
                    echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
                    echo '<div class="producto-info">';
                    echo '<h3>' . $row["nombre"] . '</h3>';
                    echo '<p class="producto-descripcion">' . $row["descripcion"] . '</p>';
                    echo '<span class="producto-precio">$' . $row["precio"] . '</span>';
                   
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No hay productos disponibles en este momento.</p>";
            }
            $conn->close();
            ?>
                <!-- Puedes agregar más productos aquí -->
            </div>
        </section>
        
    </main>


<!-- Modal de Iniciar Sesión -->
<div id="login-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" data-modal="login-modal">&times;</span>
        <div class="auth-card">
            <h2>Iniciar Sesión</h2>
            <form action="login_process.php" method="POST">
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                <div class="form-group">
                    <a href="#" class="btn-register">Crear Cuenta</a>
                </div>

                <button type="submit" class="btn-primary">Iniciar Sesión</button>
            </form>

        </div>
    </div>
</div>

<!-- Modal de Registro -->
<div id="register-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" data-modal="register-modal">&times;</span>
        <div class="auth-card">
            <h2>Registrar Nuevo Usuario</h2>
            <form class="auth-form" action="register_process.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección (opcional)</label>
                    <input type="text" id="direccion" name="direccion">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirmar Contraseña</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit" class="btn-primary">Registrar</button>
            </form>
        </div>
    </div>
</div>



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

    <script>
    document.addEventListener("DOMContentLoaded", () => {
    const botonesRequierenSesion = document.querySelectorAll(".btn-requiere-sesion");
    const loginButton = document.getElementById("btn-login");
    const registerButton = document.querySelector(".btn-register"); // Asegúrate de seleccionar correctamente el enlace "Crear Cuenta"

    // Abrir modal de inicio de sesión
    const openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "flex";
        }
    };

    // Cerrar modales
    const closeButtons = document.querySelectorAll(".close-btn");
    closeButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const modalId = btn.getAttribute("data-modal");
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = "none";
            }
        });
    });

    // Cerrar modal al hacer clic fuera de él
    window.addEventListener("click", (e) => {
        const modals = document.querySelectorAll(".modal");
        modals.forEach((modal) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    });

    // Mostrar modal de iniciar sesión si no está autenticado
    botonesRequierenSesion.forEach((boton) => {
        boton.addEventListener("click", (e) => {
            if (!userIsAuthenticated) {
                e.preventDefault(); // Prevenir acción del botón o enlace
                openModal("login-modal"); // Mostrar modal de inicio de sesión
            }
        });
    });

    // Eventos para los enlaces de iniciar sesión
    if (loginButton) {
        loginButton.addEventListener("click", (e) => {
            e.preventDefault();
            openModal("login-modal");
        });
    }

    // Agregar evento para "Crear Cuenta" que abre el modal de registro
    if (registerButton) {
        registerButton.addEventListener("click", (e) => {
            e.preventDefault();
            openModal("register-modal"); // Mostrar modal de registro
        });
    }
});




    </script>


<script>
    const userIsAuthenticated = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>

</body>
</html>


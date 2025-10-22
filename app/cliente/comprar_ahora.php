<?php
session_start();
include('../config.php');

// Inicializa las variables
$carrito_items = [];
$total_items = 1;
$total_price = 0.0;

// Verifica si los datos fueron enviados por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de que los datos de los productos están presentes en el POST
    if (isset($_POST['id_producto'], $_POST['precio_producto'])) {
        // Recibe los datos del producto desde el formulario
        $id_producto = $_POST['id_producto'];
       
        $precio_producto = $_POST['precio_producto'];
        

       

        // Almacena el producto en el arreglo de carrito
        $carrito_items[] = [
            'id_producto' => $id_producto,
            'precio' => $precio_producto,
        ];

        // Actualiza el total de items y el total a pagar
        
        $total_price += $precio_producto ;
    }
} else {
    echo "No se enviaron datos de productos.";
}

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Consulta para obtener la dirección predeterminada del usuario
    $sql = "SELECT direccion FROM usuarios WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_usuario); // Asegúrate de que $id_usuario contiene el valor correcto
        $stmt->execute();
        $stmt->bind_result($direccion_predeterminada);
        $stmt->fetch();
        $stmt->close();
    } else {
        $direccion_predeterminada = "";
    }
} else {
    echo "No hay usuario en sesión.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .resumen-carrito {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .resumen-carrito p {
            margin: 10px 0;
        }

        .form-section {
            display: flex;
            justify-content: space-between;
        }

        .form-section > .form-container {
            width: 48%;
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
            <li class="dropdown logged-in">
                <a class="nav-link">Perfil <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="perfil.php" class="dropdown-link">Información</a></li>
                    <li><a href="logout.php" class="dropdown-link">Cerrar Sesión</a></li>
                </ul>
            </li>
            <li>
                <a href="carrito.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <?php echo $total_items; ?>
                </a>
            </li>
        </ul>
    </nav>
</header>

<main class="container my-5">
    <h2>Carrito de Compras</h2>

    <div class="form-section">
        <div class="form-container">
            <form action="inicio.php" >
                <div class="mb-3">
                    <label for="metodo-pago" class="form-label">Método de Pago:</label>
                    <select id="metodo-pago" name="metodo_pago" class="form-select" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo-envio" class="form-label">Tipo de Entrega:</label>
                    <select id="tipo-envio" name="tipo_envio" class="form-select" required>
                        <option value="local">Recoger en Local</option>
                        <option value="domicilio">Mandar a Domicilio</option>
                    </select>
                </div>

                <div id="direccion-section" style="display: none;">
                    <label for="tipo-direccion" class="form-label">Dirección:</label>
                    <select id="tipo-direccion" name="tipo_direccion" class="form-select" onchange="toggleDireccion(this)">
                        <option value="predeterminada">Usar Dirección Predeterminada</option>
                        <option value="nueva">Ingresar Nueva Dirección</option>
                    </select>

                    <div class="mb-3">
                        <input type="text" id="direccion-predeterminada" name="direccion_predeterminada" class="form-control" value="<?= htmlspecialchars($direccion_predeterminada ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <input type="text" id="nueva-direccion" name="nueva_direccion" class="form-control" placeholder="Ingresa una nueva dirección" disabled>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Confirmar Pago</button>
            </form>
        </div>

        <div class="resumen-carrito">
            <h4>Resumen del Carrito</h4>
            <p>Total de productos: <?= $total_items; ?></p>
            <p>Total a pagar: $<?= number_format($total_price, 2); ?></p>
            <div id="envio-costo" style="display: none;">
                <p>Cargo de envío: $50.00</p>
                <p><strong>Total con envío: $<?= number_format($total_price + 50, 2); ?></strong></p>
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
                <a href="https://www.instagram.com/vainiyabakery"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@vainiyabakery"><i class="fab fa-tiktok"></i></a>
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

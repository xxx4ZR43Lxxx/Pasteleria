<?php
session_start();
include('../config.php'); // Incluye el archivo de conexión a la base de datos

// Inicializa las variables
$carrito_items = [];
$total_items = 0;
$total_price = 0.0;

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Consulta SQL para obtener los productos en el carrito
    $sql = "SELECT c.id_producto, p.nombre, p.imagen, p.precio, c.cantidad 
            FROM carrito c 
            JOIN productos p ON c.id_producto = p.id 
            WHERE c.id_usuario = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        // Procesa los resultados
        while ($row = $result->fetch_assoc()) {
            $row['subtotal'] = $row['precio'] * $row['cantidad'];
            $carrito_items[] = $row;
            $total_items += $row['cantidad'];
            $total_price += $row['subtotal'];
        }

        $stmt->close();
    } else {
        die("Error en la consulta: " . $conn->error);
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
                    <!-- Muestra la cantidad de productos en el carrito -->
                    <?php echo $total_items; ?>
                </a>
            </li>
        </ul>
    </nav>
</header>

<main class="container my-5">
    <h2>Carrito de Compras</h2>

    <?php if (count($carrito_items) > 0): ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito_items as $item): ?>
                    <tr>
                        <td><img src="<?= $item['imagen']; ?>" alt="Imagen del producto" style="width: 80px; height: 80px;"></td>
                        <td><?= $item['nombre']; ?></td>
                        <td>
                            <form method="POST" action="actualizar_carrito.php">
                                <div class="input-group">
                                    <input type="hidden" name="id_producto" value="<?= $item['id_producto']; ?>">
                                    <button type="submit" name="accion" value="restar" class="btn btn-outline-secondary">-</button>
                                    <input type="text" name="cantidad" value="<?= $item['cantidad']; ?>" class="form-control text-center" readonly>
                                    <button type="submit" name="accion" value="sumar" class="btn btn-outline-secondary">+</button>
                                </div>
                            </form>
                        </td>
                        <td>$<?= number_format($item['precio'], 2); ?></td>
                        <td>$<?= number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <form method="POST" action="eliminar_carrito.php">
                                <input type="hidden" name="id_producto" value="<?= $item['id_producto']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <h4>Resumen del Carrito</h4>
            <p>Total de productos: <?= $total_items; ?></p>
            <p>Total a pagar: $<?= number_format($total_price, 2); ?></p>
            <a href="comprar.php" class="btn btn-primary">Proceder al Pago</a>
        </div>
    <?php else: ?>
        <p>No hay productos en el carrito.</p>
    <?php endif; ?>
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

<?php
require "../config.php";

// Obtener los pedidos
$sql = "SELECT p.id, p.total, p.estado, p.fecha_pedido, p.direccion_entrega, u.nombre AS cliente
        FROM pedidos p
        JOIN usuarios u ON p.id_usuario = u.id
        WHERE p.estado IN ('pendiente', 'listo', 'entregado')
        ORDER BY p.estado, p.fecha_pedido DESC";
$result = $conn->query($sql);
$pedidos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .table-container {
            margin: 20px auto;
            max-width: 1000px;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .status-btn {
            padding: 5px 10px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .status-btn:hover {
            background-color: #ff1493;
        }
    </style>
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
    <div class="table-container">
        <h3>Gestión de Pedidos</h3>
        
        <!-- Sección de pedidos pendiente y listo -->
        <h4>Pedidos Pendientes y Listos</h4>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <?php if ($pedido['estado'] != 'entregado'): ?>
                        <tr>
                            <td><?= $pedido['id'] ?></td>
                            <td><?= $pedido['cliente'] ?></td>
                            <td>$<?= number_format($pedido['total'], 2) ?></td>
                            <td>
                                <select class="estado-select" data-id="<?= $pedido['id'] ?>">
                                    <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="listo" <?= $pedido['estado'] == 'listo' ? 'selected' : '' ?>>Listo</option>
                                    <option value="entregado" <?= $pedido['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                                </select>
                            </td>
                            <td>
                                <button class="status-btn actualizar-btn" data-id="<?= $pedido['id'] ?>">Actualizar Estado</button>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Sección de pedidos entregados -->
        <h4>Pedidos Entregados</h4>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <?php if ($pedido['estado'] == 'entregado'): ?>
                        <tr>
                            <td><?= $pedido['id'] ?></td>
                            <td><?= $pedido['cliente'] ?></td>
                            <td>$<?= number_format($pedido['total'], 2) ?></td>
                            <td><?= ucfirst($pedido['estado']) ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.actualizar-btn').forEach(button => {
            button.addEventListener('click', function () {
                const idPedido = this.getAttribute('data-id');
                const estado = this.closest('tr').querySelector('.estado-select').value;

                // Enviar la solicitud para actualizar el estado
                fetch('actualizar_estado.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        id_pedido: idPedido,
                        estado: estado
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();  // Recargar la página para reflejar los cambios
                    } else {
                        alert('Error al actualizar el estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al cambiar el estado del pedido.');
                });
            });
        });
    </script>


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
</body>
</html>

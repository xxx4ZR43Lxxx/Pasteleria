<?php
session_start();
require "../config.php";


// Conexión a la base de datos y obtención de datos para las gráficas
// Consulta: número de pedidos por estado
$sql_estado_pedidos = "SELECT estado, COUNT(*) as total
                       FROM pedidos
                       GROUP BY estado";
$result_estado_pedidos = $conn->query($sql_estado_pedidos);
$estado_pedidos = [];
while ($row = $result_estado_pedidos->fetch_assoc()) {
    $estado_pedidos[] = $row;
}
// Consulta: ventas totales por categoría
$sql_categoria_productos = "SELECT p.categoria, SUM(dp.subtotal) as total_ventas
                            FROM detalle_pedido dp
                            JOIN productos p ON dp.id_producto = p.id
                            GROUP BY p.categoria";
$result_categoria_productos = $conn->query($sql_categoria_productos);
$categoria_productos = [];
while ($row = $result_categoria_productos->fetch_assoc()) {
    $categoria_productos[] = $row;
}
// Consulta: ventas totales por mes
$sql_ventas_mensuales = "SELECT MONTH(fecha_pedido) as mes, SUM(total) as total_ventas
                         FROM pedidos
                         GROUP BY MONTH(fecha_pedido)";
$result_ventas_mensuales = $conn->query($sql_ventas_mensuales);
$ventas_mensuales = [];
while ($row = $result_ventas_mensuales->fetch_assoc()) {
    $ventas_mensuales[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Vainiya! Bakery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        

        .card-title {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .bi {
            margin-right: 5px;
        }

        

        .revenue-chart-placeholder,
        .user-chart-placeholder {
            min-height: 300px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .chart-container {
            height: 300px; /* Ajustar la altura de los gráficos */
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

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

    <!-- Main Content -->
    <main class="container mt-5 pt-5">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Pedidos</h5>
                        <h2><?php echo count($estado_pedidos); ?></h2>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Ventas Totales</h5>
                        <h2>$<?php echo number_format(array_sum(array_column($ventas_mensuales, 'total_ventas')), 2); ?></h2>
                        
                    </div>
                </div>
            </div>
            
            
        </div>

        <div class="row g-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Análisis de Ventas</h5>
                        <div class="chart-container p-4 bg-light">
                            <canvas id="estadoPedidos"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nueva sección de Ventas por Categoría abajo de Análisis de Ventas -->
        <div class="row g-4 mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ventas por Categoría</h5>
                        <div class="chart-container p-4 bg-light">
                            <canvas id="ventasCategoria"></canvas>
                        </div>
                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Gráfico: Estado de Pedidos
        var ctxEstadoPedidos = document.getElementById('estadoPedidos').getContext('2d');
        var estadoPedidosData = <?php echo json_encode($estado_pedidos); ?>;
        var estadoPedidosLabels = estadoPedidosData.map(item => item.estado);
        var estadoPedidosValues = estadoPedidosData.map(item => item.total);

        new Chart(ctxEstadoPedidos, {
            type: 'pie',
            data: {
                labels: estadoPedidosLabels,
                datasets: [{
                    label: 'Estado de Pedidos',
                    data: estadoPedidosValues,
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true
            }
        });

        // Gráfico: Ventas por Categoría
        var ctxVentasCategoria = document.getElementById('ventasCategoria').getContext('2d');
        var ventasCategoriaData = <?php echo json_encode($categoria_productos); ?>;
        var ventasCategoriaLabels = ventasCategoriaData.map(item => item.categoria);
        var ventasCategoriaValues = ventasCategoriaData.map(item => item.total_ventas);

        new Chart(ctxVentasCategoria, {
            type: 'bar',
            data: {
                labels: ventasCategoriaLabels,
                datasets: [{
                    label: 'Ventas por Categoría ($)',
                    data: ventasCategoriaValues,
                    backgroundColor: '#66b3ff',
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

</body>
</html>

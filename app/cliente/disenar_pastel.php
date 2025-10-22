<?php
session_start();
require "../config.php";
require "carrito_cont.php";
$precios = [
    'sabor' => [
        'vainilla' => 100,
        'chocolate' => 120,
        'fresa' => 110,
        'red-velvet' => 130
    ],
    'relleno' => [
        'crema' => 30,
        'frutas' => 40,
        'chocolate' => 50
    ],
    'cobertura' => [
        'betun' => 20,
        'fondant' => 50,
        'chocolate' => 40
    ],
    'tamano' => [
        '6 Personas' => 80,
        '15 Personas' => 150,
        '25 Personas' => 200,
        '40 Personas' => 300
    ]
];

$sql = "SELECT direccion FROM usuarios WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id_usuario); // Asegúrate de que $id_usuario contiene el valor correcto
    $stmt->execute();
    $stmt->bind_result($direccion_predeterminada);
    $stmt->fetch();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseña tu Pastel - Vainiya! Bakery</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-bottom: 50px; /* To avoid overlap with footer */
        }
        .auth-container {
            width: 100%;
            max-width: 400px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-primary {
            display: block;
            width: 100%;
            padding: 0.75rem;
            
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        h2{
            text-align: center;
            margin-top: 50px;
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


<script>
        function toggleTarjeta(select) {
            const tarjetaSection = document.getElementById("tarjeta-section");
            tarjetaSection.style.display = select.value === "efectivo" ? "none" : "block";
        }

        function toggleDireccion(select) {
    const nuevaDireccionInput = document.getElementById("nueva-direccion");
    const direccionPredeterminadaInput = document.getElementById("direccion-predeterminada");
    
    if (select.value === "nueva") {
        // Muestra el campo de nueva dirección y oculta el de dirección predeterminada
        nuevaDireccionInput.disabled = false;
        direccionPredeterminadaInput.disabled = true;
        direccionPredeterminadaInput.style.display = "none"; // Oculta dirección predeterminada
        nuevaDireccionInput.style.display = "block"; // Muestra nueva dirección
    } else {
        // Muestra el campo de dirección predeterminada y oculta el de nueva dirección
        nuevaDireccionInput.disabled = true;
        direccionPredeterminadaInput.disabled = false;
        direccionPredeterminadaInput.style.display = "block"; // Muestra dirección predeterminada
        nuevaDireccionInput.style.display = "none"; // Oculta nueva dirección
    }
}



        function toggleEnvio(select) {
            const direccionSection = document.getElementById("direccion-section");
            const envioCosto = document.getElementById("envio-costo");
            if (select.value === "domicilio") {
                direccionSection.style.display = "block";
                envioCosto.style.display = "block";
            } else {
                direccionSection.style.display = "none";
                envioCosto.style.display = "none";
            }
        }
    </script>
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
<h2>Diseña tu Pastel</h2>
<main>
    <section class="personalizar-pastel auth-container">
        <form action="compra_pastel_personalizado.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="sabor">Sabor del Pastel</label>
                <select id="sabor" name="sabor" required onchange="actualizarPrecio()">
                    <option value="vainilla" data-precio="100">Vainilla</option>
                    <option value="chocolate" data-precio="120">Chocolate</option>
                    <option value="fresa" data-precio="110">Fresa</option>
                    <option value="red-velvet" data-precio="130">Red Velvet</option>
                </select>
            </div>

            <div class="form-group">
                <label for="relleno">Relleno</label>
                <select id="relleno" name="relleno" required onchange="actualizarPrecio()">
                    <option value="crema" data-precio="30">Crema</option>
                    <option value="frutas" data-precio="40">Frutas</option>
                    <option value="chocolate" data-precio="50">Chocolate</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cobertura">Cobertura</label>
                <select id="cobertura" name="cobertura" required onchange="actualizarPrecio()">
                    <option value="betun" data-precio="20">Betún</option>
                    <option value="fondant" data-precio="50">Fondant</option>
                    <option value="chocolate" data-precio="40">Chocolate</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tamano">Tamaño</label>
                <select id="tamano" name="tamano" required onchange="actualizarPrecio()">
                    <option value="6 Personas" data-precio="80">6 Personas</option>
                    <option value="15 Personas" data-precio="150">15 Personas</option>
                    <option value="25 Personas" data-precio="200">25 Personas</option>
                    <option value="40 Personas" data-precio="300">40 Personas</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mensaje">Mensaje en el Pastel</label>
                <input type="text" id="mensaje" name="mensaje" placeholder="Feliz Cumpleaños" maxlength="50">
            </div>

            <div class="form-group">
                <label for="imagen">Imagen de Referencia (opcional)</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>

            

            <div class="form-group">
                    <label for="metodo-pago" class="form-label">Método de Pago:</label>
                    <select id="metodo-pago" name="metodo_pago" class="form-select" onchange="toggleTarjeta(this)" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>

                <div id="tarjeta-section" class="form-group" style="display: none;">
                    <label for="numero-tarjeta" class="form-label">Número de Tarjeta:</label>
                    <input type="text" id="numero-tarjeta" name="numero_tarjeta" class="form-control" placeholder="1234-5678-9012-3456" maxlength="19">
                    <label for="fecha-vencimiento" class="form-label">Fecha de Vencimiento:</label>
                    <input type="month" id="fecha-vencimiento" name="fecha_vencimiento" class="form-control">
                    <label for="cvv" class="form-label">CVV:</label>
                    <input type="password" id="cvv" name="cvv" class="form-control" placeholder="123" maxlength="3">
                </div>

                <div class="form-group">
                    <label for="tipo-envio" class="form-label">Tipo de Entrega:</label>
                    <select id="tipo-envio" name="tipo_envio" class="form-select" onchange="toggleEnvio(this)" required>
                        <option value="local">Recoger en Local</option>
                        <option value="domicilio">Mandar a Domicilio</option>
                    </select>
                </div>

                <div id="direccion-section" style="display: none;" class="form-group">
                    <label for="tipo-direccion" class="form-label">Dirección:</label>
                        <select id="tipo-direccion" name="tipo_direccion" class="form-select" onchange="toggleDireccion(this)">
                        <option value="predeterminada">Usar Dirección Predeterminada</option>
                        <option value="nueva">Ingresar Nueva Dirección</option>
                        </select>

                        <!-- Input de dirección predeterminada -->
                        <div class="form-group">
                        <input type="text" id="direccion-predeterminada" name="direccion_predeterminada" class="form-control" value="<?= htmlspecialchars($direccion_predeterminada ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>

                        <!-- Input de nueva dirección -->
                        <div class="form-group">
                        <input type="text" id="nueva-direccion" name="nueva_direccion" class="form-control" placeholder="Ingresa una nueva dirección" disabled>
                        </div>
                </div>


            <div class="form-group">
                <h3>Total: $<span id="total">0</span></h3>
            </div>
            <input type="hidden" name="total" id="total-hidden">


            <button type="submit" class="btn-primary">Enviar Pedido</button>
        </form>
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

<script>
    // Mostrar previsualización de la imagen seleccionada

    const inputImagen = document.getElementById('imagen');
inputImagen.addEventListener('change', function () {
    const previewContainer = inputImagen.parentElement.querySelector('.image-preview');
    
    // Si ya existe una previsualización, la eliminamos
    if (previewContainer) {
        previewContainer.remove();
    }
    
    // Si el usuario seleccionó un archivo
    const [file] = inputImagen.files;
    if (file) {
        const preview = document.createElement('div');
        preview.classList.add('image-preview');
        
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = "100%";
        img.style.marginTop = "1rem";
        
        preview.appendChild(img);
        inputImagen.insertAdjacentElement('afterend', preview);
    }
});


function actualizarPrecio() {
    const sabor = document.querySelector('#sabor option:checked');
    const relleno = document.querySelector('#relleno option:checked');
    const cobertura = document.querySelector('#cobertura option:checked');
    const tamano = document.querySelector('#tamano option:checked');

    const total = parseInt(sabor.dataset.precio) + parseInt(relleno.dataset.precio) + parseInt(cobertura.dataset.precio) + parseInt(tamano.dataset.precio);
     
    document.getElementById('total').textContent = total;

    // Actualizar el campo hidden con el valor total
    document.getElementById('total-hidden').value = total;
}


    actualizarPrecio();  // Llamamos la función al cargar la página
</script>

</body>
</html>
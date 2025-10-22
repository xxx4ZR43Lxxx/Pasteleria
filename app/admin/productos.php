<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Productos</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
    --primary-color: #FF1493;
    --secondary-color: #FFB6C1;
    --background-color: #FFF0F5;
    --text-color: #333;
    --white: #FFFFFF;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
  }

  .top-actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
  }

  .btn-add-product {
    background-color: var(--primary-color);
    color: var(--white);
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
  }

  .product-grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }

  .card {
    background-color: var(--white);
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px);
    padding: 20px;
    text-align: center;
    position: relative;
  }

  .card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
  }

  .btn-add,
  .btn-edit,
  .btn-delete {
    background-color: var(--secondary-color);
    color: var(--white);
    margin-top: 10px;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .btn-edit {
    background-color: #ff69b4;
    margin-right: 15px;
  }

  .btn-delete {
    background-color: #ff91c5;
    margin-left: 15px;
  }

  .form-container {
display: none;
position: fixed;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
background-color: var(--white);
box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
border-radius: 10px;
z-index: 1000;
padding: 20px;
width: 90%;
max-width: 400px;
}

.form-container h3 {
margin-bottom: 15px;
color: var(--primary-color);
text-align: center;
font-size: 1.5rem;
}

.form-container input,
.form-container textarea {
width: 100%;
padding: 10px;
margin-bottom: 15px;
border: 1px solid #ddd;
border-radius: 5px;
}

.form-container button {
background-color: var(--primary-color);
color: var(--white);
padding: 10px;
border: none;
border-radius: 5px;
cursor: pointer;
width: 100%;
}


  .overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    z-index: 999;
  }

  .social-links a {
    color: #ff69b4;
    margin-right: 10px;
    font-size: 1.5rem;
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

  <div class="container">
    <div class="top-actions">
      <button class="btn-add-product" id="addProductButton">+ Agregar Producto</button>
    </div>
    <div class="product-grid" id="productGrid">
      <?php
        require "../config.php";
        $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card">';
                echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
                echo '<h3>' . $row["nombre"] . '</h3>';
                echo '<p>' . $row["descripcion"] . '</p>';
                echo '<p>$' . $row["precio"] . ' MXN</p>';
                echo '<button class="btn btn-edit" data-id="' . $row["id"] . '">Editar</button>';
                echo '<button class="btn btn-delete" data-id="' . $row["id"] . '">Eliminar</button>';
                echo '</div>';
            }
        } else {
            echo "<p>No hay productos disponibles en este momento.</p>";
        }
        $conn->close();
      ?>
    </div>
  </div>

  <div class="overlay" id="overlay"></div>
  <div class="form-container" id="productFormContainer">
    <h3 id="formTitle">Agregar Producto</h3>
    <form id="productForm" enctype="multipart/form-data" method="POST">
      <input type="hidden" id="productId">
      <label for="productName">Nombre del Producto</label>
      <input type="text" id="productName" placeholder="Nombre del producto" required>

      <label for="productPrice">Precio (MXN)</label>
      <input type="number" id="productPrice" placeholder="Precio" required>

      <label for="productDescription">Descripción</label>
      <textarea id="productDescription" placeholder="Descripción" required></textarea>

      <label for="productImage">Imagen del Producto</label>
      <input type="file" id="productImage" accept="image/*">
      <!-- Vista previa de la imagen cargada -->
<img id="imagePreview" src="" alt="Vista previa de la imagen" style="display: none; max-width: 100%; margin-top: 10px;">

      <button type="button" class="btn" id="saveProduct">Guardar Producto</button>
    </form>
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

  <script>
  const addProductButton = document.getElementById('addProductButton');
  const productFormContainer = document.getElementById('productFormContainer');
  const overlay = document.getElementById('overlay');
  const saveProductButton = document.getElementById('saveProduct');
  const productGrid = document.getElementById('productGrid');
  const formTitle = document.getElementById('formTitle');
  const productIdField = document.getElementById('productId');

  // Mostrar el formulario para agregar un nuevo producto
  addProductButton.addEventListener('click', () => {
    formTitle.textContent = 'Agregar Producto';
    productIdField.value = ''; // Limpiar ID para nuevo producto
    document.getElementById('productForm').reset(); // Resetear el formulario
    productFormContainer.style.display = 'block';
    overlay.style.display = 'block';
  });

  // Cerrar el formulario al hacer clic en la superposición
  overlay.addEventListener('click', () => {
    productFormContainer.style.display = 'none';
    overlay.style.display = 'none';
  });

// Mostrar el formulario para editar un producto
productGrid.addEventListener('click', (event) => {
  if (event.target.classList.contains('btn-edit')) {
    const productId = event.target.getAttribute('data-id');
    fetch(`getProductData.php?id=${productId}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
        } else {
          formTitle.textContent = 'Editar Producto';
          productIdField.value = data.id;
          document.getElementById('productName').value = data.nombre;
          document.getElementById('productPrice').value = data.precio;
          document.getElementById('productDescription').value = data.descripcion;

          // Precargar la imagen en el modal
          const productImagePreview = document.getElementById('imagePreview');
          productImagePreview.src = data.imagen; // Ruta de la imagen
          productImagePreview.style.display = 'block'; // Mostrar imagen

          // Mostrar el formulario
          productFormContainer.style.display = 'block';
          overlay.style.display = 'block';
        }
      });
  }

    // Eliminar producto
    if (event.target.classList.contains('btn-delete')) {
      const productId = event.target.getAttribute('data-id');
      const confirmation = confirm('¿Estás seguro de eliminar este producto?');

      if (confirmation) {
        fetch('deleteProduct.php', {
          method: 'POST',
          body: new URLSearchParams({ id: productId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Producto eliminado correctamente');
            window.location.href = '/admin/productos.php'; // Redirigir a la lista de productos
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Hubo un error al eliminar el producto');
        });
      }
    }
  });

// Guardar o actualizar producto
saveProductButton.addEventListener('click', () => {
  const productId = productIdField.value;
  const productName = document.getElementById('productName').value;
  const productPrice = document.getElementById('productPrice').value;
  const productDescription = document.getElementById('productDescription').value;
  const productImage = document.getElementById('productImage').files[0]; // Capturar la imagen

  

  const formData = new FormData();
  formData.append('nombre', productName);
  formData.append('precio', productPrice);
  formData.append('descripcion', productDescription);
  formData.append('imagen', productImage); // Añadir la imagen al FormData

  let url = 'addProduct.php'; // URL para agregar producto
  if (productId) {
    url = 'editProduct.php'; // URL para editar producto
    formData.append('id', productId);
  }

  // Enviar datos al servidor
  fetch(url, {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert('Producto guardado correctamente');
        window.location.href = '/admin/productos.php'; // Redirigir a la lista de productos
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error('Error:', error);
      alert('Hubo un error al guardar el producto');
    });
});


// Función para mostrar la vista previa de la imagen seleccionada
document.getElementById('productImage').addEventListener('change', function(event) {
  const file = event.target.files[0];
  const preview = document.getElementById('imagePreview');
  
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block'; // Mostrar la vista previa
    };
    reader.readAsDataURL(file);
  } else {
    preview.src = '';
    preview.style.display = 'none'; // Ocultar la vista previa si no hay imagen seleccionada
  }
});


</script>


</body>
</html>

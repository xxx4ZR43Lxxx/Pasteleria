let currentProductId = null;

// Display products in admin panel
function displayAdminProducts() {
    const container = document.getElementById('admin-productos-container');
    container.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'producto-card';
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <div class="producto-info">
                <h3>${product.name}</h3>
                <p>${product.description}</p>
                <p class="producto-precio">$${product.price} MXN</p>
                <div class="producto-actions">
                    <button class="btn-primary btn-edit" onclick="editProduct(${product.id})">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn-primary btn-delete" onclick="deleteProduct(${product.id})">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        `;
        container.appendChild(productCard);
    });
}

// Show modal for adding/editing product
function showAddProductModal(productId = null) {
    const modal = document.getElementById('productModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('productForm');

    currentProductId = productId;

    if (productId) {
        const product = products.find(p => p.id === productId);
        modalTitle.textContent = 'Editar Producto';
        document.getElementById('productName').value = product.name;
        document.getElementById('productDescription').value = product.description;
        document.getElementById('productPrice').value = product.price;
    } else {
        modalTitle.textContent = 'Agregar Nuevo Producto';
        form.reset();
    }

    modal.style.display = 'block';
}

// Handle form submission
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const productData = {
        name: document.getElementById('productName').value,
        description: document.getElementById('productDescription').value,
        price: parseFloat(document.getElementById('productPrice').value),
        image: '/placeholder.svg?height=200&width=300' // In a real app, handle image upload
    };

    if (currentProductId) {
        // Update existing product
        const index = products.findIndex(p => p.id === currentProductId);
        products[index] = { ...products[index], ...productData };
    } else {
        // Add new product
        productData.id = products.length + 1;
        products.push(productData);
    }

    document.getElementById('productModal').style.display = 'none';
    displayAdminProducts();
});

// Delete product
function deleteProduct(productId) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        products = products.filter(p => p.id !== productId);
        displayAdminProducts();
    }
}

// Close modal
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('productModal').style.display = 'none';
});

// Initialize admin panel
document.addEventListener('DOMContentLoaded', () => {
    displayAdminProducts();
});


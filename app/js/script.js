// Product data
const products = [
    {
        id: 1,
        name: 'Pastel de Chocolate',
        description: 'Delicioso pastel de chocolate con ganache',
        price: 350,
        image: '/placeholder.svg?height=200&width=200'
    },
    {
        id: 2,
        name: 'Tarta de Fresa',
        description: 'Tarta fresca con fresas naturales',
        price: 280,
        image: '/placeholder.svg?height=200&width=200'
    },
    {
        id: 3,
        name: 'Cupcakes de Vainilla',
        description: 'Suaves cupcakes con frosting de vainilla',
        price: 45,
        image: '/placeholder.svg?height=200&width=200'
    },
    {
        id: 4,
        name: 'Pastel Red Velvet',
        description: 'Exquisito pastel Red Velvet con crema de queso',
        price: 380,
        image: '/placeholder.svg?height=200&width=200'
    },
    {
        id: 5,
        name: 'Cheesecake de Frambuesa',
        description: 'Cremoso cheesecake con salsa de frambuesa',
        price: 320,
        image: '/placeholder.svg?height=200&width=200'
    },
    {
        id: 6,
        name: 'Macarons Surtidos',
        description: 'Delicada selección de macarons en varios sabores',
        price: 180,
        image: '/placeholder.svg?height=200&width=200'
    }
];

// Function to display products
function displayProducts() {
    const container = document.getElementById('productos-container');
    container.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'producto-card';
        productCard.innerHTML = `
            <h3>${product.name}</h3>
            <img src="${product.image}" alt="${product.name}">
            <div class="producto-content">
                <p class="producto-description">${product.description}</p>
                <p class="producto-price">$${product.price} MXN</p>
                <button class="btn-details">Ver Detalles</button>
            </div>
        `;
        container.appendChild(productCard);
    });
}

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
    displayProducts();
});


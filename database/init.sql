-- CREACIÓN DE LA BASE DE DATOS
USE vainiya_bakery;

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    numero_contacto VARCHAR(20),
    direccion TEXT,
    tipo_usuario VARCHAR(50) DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
    categoria VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(50) DEFAULT 'pendiente',
    metodo_pago VARCHAR(50),
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    direccion_entrega TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla: detalle_pedido
CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Tabla: personalizacion_pastel
CREATE TABLE personalizacion_pastel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    sabor VARCHAR(255) NOT NULL,
    relleno VARCHAR(255),
    diseno TEXT,
    mensaje VARCHAR(255),
    decoracion TEXT,
    tamano_porciones VARCHAR(50),
    imagen_referencia VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE
);

CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL, -- NULL si es un usuario no registrado
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);


-- EJEMPLOS DE INSERCIÓN

-- Insertar usuarios pass:123


INSERT INTO usuarios (nombre, correo, contrasena, numero_contacto, direccion, tipo_usuario)
VALUES 
('Juan Perez', 'juan.perez@email.com', '$2y$10$3zLse5E6buVWSytIdNv53uodEKsnDAaLiUg99g8/QjaLBdBMdZMtK', '1234567890', 'Calle Falsa 123', 'cliente'),
('Admin Bakery', 'admin@email.com', '$2y$10$3zLse5E6buVWSytIdNv53uodEKsnDAaLiUg99g8/QjaLBdBMdZMtK', NULL, NULL, 'administrador');

INSERT INTO productos (nombre, descripcion, precio, imagen, categoria)
VALUES
    ('Budin de Elote', 'Delicioso budin con el sabor dulce del maiz', 450.00, '/img/postres/budin_de_elote.png', 'Postres'),
    ('Flan Napolitano de Queso', 'Clasico flan con un toque de queso', 520.00, '/img/postres/flan_napolitano_queso.png', 'Flan'),
    ('Gelatina de Chocolate', 'Refrescante gelatina con intenso sabor a chocolate', 380.00, '/img/postres/gealtina_de_chocolate.png', 'Gelatina'),
    ('Gelatina de Coco', 'Gelatina con el exotico sabor del coco', 400.00, '/img/postres/gelatina_de_coco.png', 'Gelatina'),
    ('Gelatina de Durazno con 3 Leches', 'Gelatina cremosa con el sabor del durazno y tres leches', 420.00, '/img/postres/gelatina_de_durazno_con_3_leches.png', 'Gelatina'),
    ('Gelatina de Mosaico', 'Gelatina con diferentes capas de colores y sabores', 450.00, '/img/postres/gelatina_de_mosaico.png', 'Gelatina'),
    ('Gelatina de Zanahoria, Pina, Nuez y Queso', 'Gelatina con una combinacion de sabores y texturas', 480.00, '/img/postres/gelatina_de_zanahoria_pina_nuez_queso.png', 'Gelatina'),
    ('Gelatina de Cajeta', 'Gelatina con el dulce sabor de la cajeta', 420.00, '/img/postres/geletani_de_cajeta.png', 'Gelatina'),
    ('Mousse de Durazno', 'Suave mousse con el sabor del durazno', 350.00, '/img/postres/mousse_de_durazno.png', 'Mousse'),
    ('Mousse de Fresa', 'Mousse ligero y refrescante con sabor a fresa', 350.00, '/img/postres/mousse_de_fresa.png', 'Mousse'),
    ('Mousse de Mango', 'Exotica mousse con el sabor tropical del mango', 380.00, '/img/postres/mousse_de_mango.png', 'Mousse')
;
-- Insertar un pedido
INSERT INTO pedidos (id_usuario, total, estado, metodo_pago, direccion_entrega)
VALUES 
(1, 46.98, 'pendiente', 'online', 'Calle Falsa 123');

-- Insertar detalle del pedido
INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, subtotal)
VALUES 
(1, 1, 1, 25.99),
(1, 2, 1, 18.99);

-- Insertar personalización del pastel
INSERT INTO personalizacion_pastel (id_pedido, sabor, tipo, relleno, diseno, mensaje, decoracion, tamano_porciones)
VALUES 
(1, 'Chocolate', 'Clásico', 'Fresa', 'Decoración floral con colores pastel', 'Feliz Cumpleaños Juan', 'Flores de azúcar', '12 porciones');

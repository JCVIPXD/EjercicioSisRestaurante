-- ============================================================
-- SisRestaurante - Script de base de datos
-- Motor: MySQL 8 / MariaDB 10.4+
-- Uso: importar desde phpMyAdmin o ejecutar con mysql -u root -p
-- ============================================================

CREATE DATABASE IF NOT EXISTS sisrestaurante
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_spanish_ci;

USE sisrestaurante;

-- ============================================================
-- TABLA: usuarios  (acceso al sistema interno)
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(120) NOT NULL,
  correo      VARCHAR(180) NOT NULL UNIQUE,
  clave       VARCHAR(255) NOT NULL,              -- bcrypt hash
  rol         ENUM('admin','empleado') NOT NULL DEFAULT 'empleado',
  activo      TINYINT(1) NOT NULL DEFAULT 1,
  creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: clientes  (publico que hace pedidos)
-- ============================================================
CREATE TABLE IF NOT EXISTS clientes (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(120) NOT NULL,
  telefono    VARCHAR(20)  NOT NULL,
  direccion   VARCHAR(255) NOT NULL,
  activo      TINYINT(1) NOT NULL DEFAULT 1,      -- eliminacion logica
  creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: categorias
-- ============================================================
CREATE TABLE IF NOT EXISTS categorias (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(80) NOT NULL UNIQUE,
  slug        VARCHAR(80) NOT NULL UNIQUE,
  activo      TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: productos
-- ============================================================
CREATE TABLE IF NOT EXISTS productos (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NOT NULL,
  nombre       VARCHAR(120) NOT NULL,
  descripcion  TEXT,
  precio       DECIMAL(8,2) NOT NULL,
  imagen       VARCHAR(512),
  activo       TINYINT(1) NOT NULL DEFAULT 1,     -- eliminacion logica
  creado_en    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_producto_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: empleados
-- ============================================================
CREATE TABLE IF NOT EXISTS empleados (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id  INT UNSIGNED,                       -- vinculo opcional a acceso
  nombre      VARCHAR(120) NOT NULL,
  rol         VARCHAR(80)  NOT NULL,              -- Cocinero, Cajero, Repartidor
  turno       ENUM('manana','tarde','noche') NOT NULL DEFAULT 'tarde',
  telefono    VARCHAR(20),
  activo      TINYINT(1) NOT NULL DEFAULT 1,
  creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_empleado_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: pedidos
-- ============================================================
CREATE TABLE IF NOT EXISTS pedidos (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id   INT UNSIGNED NOT NULL,
  producto_id  INT UNSIGNED NOT NULL,
  cantidad     SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  total        DECIMAL(10,2) NOT NULL,
  forma_entrega ENUM('delivery','recojo') NOT NULL DEFAULT 'delivery',
  observaciones TEXT,
  estado       ENUM('pendiente','en_preparacion','enviado','entregado') NOT NULL DEFAULT 'pendiente',
  fecha        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pedido_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_pedido_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: pagos
-- ============================================================
CREATE TABLE IF NOT EXISTS pagos (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id   INT UNSIGNED NOT NULL UNIQUE,       -- un pago por pedido
  monto       DECIMAL(10,2) NOT NULL,
  metodo      ENUM('efectivo','yape','plin','tarjeta') NOT NULL DEFAULT 'efectivo',
  fecha       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pago_pedido
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- DATOS INICIALES
-- ============================================================

-- Usuarios del sistema interno
-- Contrasena: Admin2026* | hash bcrypt generado con PASSWORD_BCRYPT, cost=12
INSERT INTO usuarios (nombre, correo, clave, rol) VALUES
  ('Administrador', 'admin@sisrestaurante.com',
   '$2y$12$oKpKWFJ3lgWLWuK.b1r0bOzF8v7sUgdRHcMt3NpVSPaRElE2mT0sy', 'admin'),
-- Contrasena: Empleado2026*
  ('Alex Rios',     'alex@sisrestaurante.com',
   '$2y$12$QmXkz5nL0OWo2sJ9t4Hd8eSvGJzBuyNdtH9K6n2pTVDIaKkHLUjXa', 'empleado'),
  ('Diana Cruz',    'diana@sisrestaurante.com',
   '$2y$12$QmXkz5nL0OWo2sJ9t4Hd8eSvGJzBuyNdtH9K6n2pTVDIaKkHLUjXa', 'empleado');

-- Categorias (slugs alineados con data-filter del front)
INSERT INTO categorias (nombre, slug) VALUES
  ('Hamburguesas', 'hamburguesas'),
  ('Pizzas',       'pizzas'),
  ('Bebidas',      'bebidas'),
  ('Postres',      'postres'),
  ('Almuerzos',    'almuerzos'),
  ('Combos',       'combos');

-- Productos (alineados con los del catalogo publico)
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen) VALUES
  (1, 'Burger Fuego',    'Carne angus, cheddar, cebolla crispy y salsa de la casa.',     22.90,
   'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80'),
  (2, 'Pizza Andina',    'Queso mozzarella, pepperoni y vegetales asados.',              35.50,
   'https://images.unsplash.com/photo-1548365328-8b849e1e2f2d?auto=format&fit=crop&w=900&q=80'),
  (3, 'Limonada frozen', 'Limon natural, hierbabuena y toque de gengibre.',               9.90,
   'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=900&q=80'),
  (4, 'Brownie volcan',  'Brownie tibio con helado de vainilla y fudge.',                14.50,
   'https://images.unsplash.com/photo-1606313564200-e75d5e30476a?auto=format&fit=crop&w=900&q=80'),
  (5, 'Lomo saltado',    'Filete salteado, papas crocantes y arroz graneado.',           28.00,
   'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=80'),
  (6, 'Combo Local',     'Hamburguesa clasica, papas y bebida mediana.',                 29.90,
   'https://images.unsplash.com/photo-1571091718767-18b5b1457add?auto=format&fit=crop&w=900&q=80'),
  -- Productos adicionales por categoria para tener variedad
  (1, 'Burger Clasica',  'Res, lechuga, tomate y mostaza artesanal.',                   18.50, NULL),
  (2, 'Pizza 4 Quesos',  'Mozzarella, gouda, parmesano y queso azul.',                  38.00, NULL),
  (3, 'Jugo de naranja', 'Naranja fresca exprimida al momento.',                          7.00, NULL),
  (5, 'Arroz con pollo', 'Pollo en salsa verde con arroz graneado y ensalada.',          25.00, NULL),
  (4, 'Cheesecake',      'Tarta de queso con mermelada de frutos rojos.',                12.00, NULL),
  (6, 'Combo Familiar',  'Dos pizzas medianas y cuatro bebidas.',                        75.00, NULL);

-- Empleados
INSERT INTO empleados (usuario_id, nombre, rol, turno, telefono) VALUES
  (2, 'Alex Rios',   'Cocinero',    'tarde',   '951100200'),
  (3, 'Diana Cruz',  'Cajero',      'manana',  '962200100'),
  (NULL, 'Carlos Vera',  'Repartidor',  'tarde',   '943300150'),
  (NULL, 'Sofia Nunez',  'Cocinero',    'manana',  '934400160'),
  (NULL, 'Pedro Salas',  'Repartidor',  'noche',   '925500170');

-- Clientes de muestra
INSERT INTO clientes (nombre, telefono, direccion) VALUES
  ('Lucia Perez',   '999777111', 'Av. Los Pinos 234, Miraflores'),
  ('Marco Diaz',    '988666222', 'Jr. Tupac Amaru 56, San Isidro'),
  ('Rosa Leon',     '977555333', 'Calle Las Flores 89, Surco'),
  ('Jose Medina',   '966444444', 'Av. Brasil 410, Pueblo Libre'),
  ('Carmen Rios',   '955333555', 'Pasaje El Sol 12, La Molina'),
  ('Luis Torres',   '944222666', 'Jr. Cusco 78, Breña');

-- Marcar cliente con eliminacion logica para ejemplo
UPDATE clientes SET activo = 0 WHERE nombre = 'Jose Medina';

-- Pedidos de muestra (con distintos estados)
INSERT INTO pedidos (cliente_id, producto_id, cantidad, total, forma_entrega, observaciones, estado, fecha) VALUES
  (1, 2, 2, 71.00,  'delivery',  'Sin pimiento por favor',       'pendiente',       '2026-05-14 10:30:00'),
  (2, 1, 1, 22.90,  'delivery',  NULL,                           'en_preparacion',  '2026-05-14 11:05:00'),
  (3, 6, 1, 29.90,  'recojo',    'Recoger a las 2pm',            'entregado',       '2026-05-13 13:45:00'),
  (5, 5, 2, 56.00,  'delivery',  'Extra salsa criolla',          'enviado',         '2026-05-14 12:00:00'),
  (1, 3, 3, 29.70,  'recojo',    NULL,                           'entregado',       '2026-05-12 09:20:00'),
  (6, 4, 1, 14.50,  'delivery',  'Sin fudge',                    'pendiente',       '2026-05-14 12:30:00');

-- Pagos de muestra (solo pedidos con estado entregado o enviado)
INSERT INTO pagos (pedido_id, monto, metodo, fecha) VALUES
  (3, 29.90, 'efectivo', '2026-05-13 14:00:00'),
  (5, 29.70, 'yape',     '2026-05-12 09:25:00');

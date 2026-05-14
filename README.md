# SisRestaurante

Proyecto academico para un restaurante local con modulo publico y panel interno.

## Stack visual usado

- Bootstrap 5 para layout y componentes base.
- Estilos personalizados con diseno creativo, tokens de color y animaciones ligeras.
- JavaScript vanilla para filtros de menu, formulario y busquedas en tablas.

## Pantallas implementadas

- Home publico con informacion del restaurante, horario, ubicacion y menu en tarjetas.
- Formulario de pedido con nombre, telefono, direccion, producto, cantidad, entrega y observaciones.
- Login administrativo.
- Dashboard administrativo con indicadores y modulos de clientes, productos, categorias, pedidos, empleados y pagos.

## Cobertura del enunciado

- Catalogo visual por categorias: hamburguesas, pizzas, bebidas, postres, almuerzos y combos.
- Tarjetas de producto con imagen, nombre, descripcion, precio y accion para pedir.
- Registro de pedido desde formulario.
- Dashboard con productos, pedidos pendientes, pedidos entregados, clientes y pagos.
- Tablas principales con busqueda.
- Zona de reportes por estado de pedido y por fecha de pago.
- Referencias de eliminacion logica para clientes y productos.

## Rutas de prueba

- Home: /SisRestaurante/index.php
- Login admin: /SisRestaurante/admin/login.php
- Dashboard: /SisRestaurante/admin/dashboard.php

## Guia rapida (super simple)

### 1) Usar como cliente (pagina publica)

1. Entra a `/SisRestaurante/index.php`.
2. Revisa el menu por categorias y elige un producto.
3. Haz clic en **Pedir ahora** o llena el formulario manualmente.
4. Completa nombre, telefono, direccion, producto y cantidad.
5. Pulsa **Enviar pedido**.
6. Si todo salio bien, veras un mensaje de confirmacion.

### 2) Usar como administrador

1. Entra a `/SisRestaurante/admin/login.php`.
2. Inicia sesion con:
	- Correo: `admin@sisrestaurante.com`
	- Clave: `Admin2026*`
3. En el dashboard revisa los indicadores principales.
4. En el menu lateral administra: clientes, productos, categorias, pedidos, empleados y pagos.
5. Para salir, usa la opcion **Cerrar sesion**.

### 3) Tip rapido si no deja ingresar

1. Verifica que la base `sisrestaurante` este importada.
2. Asegura que estas usando exactamente el correo y clave de arriba.
3. Si persiste, recarga la pagina con `Ctrl + F5`.

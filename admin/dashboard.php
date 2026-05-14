<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | SisRestaurante</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <header class="navbar navbar-expand-lg">
    <div class="container py-2">
      <a class="navbar-brand brand" href="../index.php">Sabor Local</a>
      <div class="ms-auto d-flex gap-2">
        <a href="../index.php" class="btn btn-outline-main">Ver home</a>
        <a href="login.php" class="btn btn-main">Cerrar sesion</a>
      </div>
    </div>
  </header>

  <main class="container py-4">
    <section class="mb-4">
      <p class="eyebrow mb-1">Panel administrativo</p>
      <h1 class="section-title mb-2">Dashboard</h1>
      <p class="text-secondary mb-0">Control basico de productos, pedidos, clientes, empleados y pagos.</p>
    </section>

    <section class="row g-3 mb-4" aria-label="Indicadores">
      <div class="col-6 col-lg-2"><article class="info-card"><h2 class="h6">Productos</h2><p class="h3 mb-0">48</p></article></div>
      <div class="col-6 col-lg-2"><article class="info-card"><h2 class="h6">Pendientes</h2><p class="h3 mb-0">12</p></article></div>
      <div class="col-6 col-lg-2"><article class="info-card"><h2 class="h6">Entregados</h2><p class="h3 mb-0">107</p></article></div>
      <div class="col-6 col-lg-2"><article class="info-card"><h2 class="h6">Clientes</h2><p class="h3 mb-0">220</p></article></div>
      <div class="col-6 col-lg-2"><article class="info-card"><h2 class="h6">Pagos</h2><p class="h3 mb-0">S/ 8,920</p></article></div>
      <div class="col-6 col-lg-2"><article class="info-card"><h2 class="h6">Empleados</h2><p class="h3 mb-0">9</p></article></div>
    </section>

    <section class="mb-4">
      <div class="order-form">
        <h2 class="h4 mb-3">Gestion de pedidos</h2>
        <div class="row g-3 mb-3">
          <div class="col-12 col-md-4">
            <label for="searchPedido" class="form-label">Buscar pedido</label>
            <input id="searchPedido" type="search" class="form-control" placeholder="Cliente, producto o estado">
          </div>
          <div class="col-12 col-md-3">
            <label for="filtroEstado" class="form-label">Estado</label>
            <select id="filtroEstado" class="form-select">
              <option value="">Todos</option>
              <option>Pendiente</option>
              <option>En preparacion</option>
              <option>Enviado</option>
              <option>Entregado</option>
            </select>
          </div>
        </div>
        <div class="table-responsive">
          <table id="tablaPedidos" class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>301</td><td>Lucia Perez</td><td>Pizza Andina</td><td>2</td><td>S/ 71.00</td><td>Pendiente</td><td>2026-05-14</td></tr>
              <tr><td>302</td><td>Marco Diaz</td><td>Burger Fuego</td><td>1</td><td>S/ 22.90</td><td>En preparacion</td><td>2026-05-14</td></tr>
              <tr><td>303</td><td>Rosa Leon</td><td>Combo Local</td><td>1</td><td>S/ 29.90</td><td>Entregado</td><td>2026-05-13</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="row g-4">
      <div class="col-12 col-lg-6">
        <article class="order-form h-100">
          <h2 class="h4 mb-3">Gestion de clientes</h2>
          <label for="searchCliente" class="form-label">Buscar cliente</label>
          <input id="searchCliente" type="search" class="form-control mb-3" placeholder="Nombre o telefono">
          <div class="table-responsive">
            <table id="tablaClientes" class="table align-middle mb-0">
              <thead><tr><th>Nombre</th><th>Telefono</th><th>Estado</th></tr></thead>
              <tbody>
                <tr><td>Lucia Perez</td><td>999777111</td><td>Activo</td></tr>
                <tr><td>Jose Medina</td><td>955220011</td><td>Inactivo (logico)</td></tr>
              </tbody>
            </table>
          </div>
        </article>
      </div>

      <div class="col-12 col-lg-6">
        <article class="order-form h-100">
          <h2 class="h4 mb-3">Gestion de productos y categorias</h2>
          <label for="searchProducto" class="form-label">Buscar producto</label>
          <input id="searchProducto" type="search" class="form-control mb-3" placeholder="Nombre o categoria">
          <div class="table-responsive mb-3">
            <table id="tablaProductos" class="table align-middle mb-0">
              <thead><tr><th>Producto</th><th>Categoria</th><th>Precio</th></tr></thead>
              <tbody>
                <tr><td>Burger Fuego</td><td>Hamburguesas</td><td>S/ 22.90</td></tr>
                <tr><td>Pizza Andina</td><td>Pizzas</td><td>S/ 35.50</td></tr>
              </tbody>
            </table>
          </div>
          <p class="small mb-0 text-secondary">Incluye eliminacion logica para productos fuera de catalogo.</p>
        </article>
      </div>

      <div class="col-12 col-lg-6">
        <article class="order-form h-100">
          <h2 class="h4 mb-3">Gestion de empleados</h2>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead><tr><th>Empleado</th><th>Rol</th><th>Turno</th></tr></thead>
              <tbody>
                <tr><td>Alex Rios</td><td>Cocina</td><td>Tarde</td></tr>
                <tr><td>Diana Cruz</td><td>Caja</td><td>Manana</td></tr>
              </tbody>
            </table>
          </div>
        </article>
      </div>

      <div class="col-12 col-lg-6">
        <article class="order-form h-100">
          <h2 class="h4 mb-3">Gestion de pagos</h2>
          <label for="fechaPago" class="form-label">Reporte por fecha</label>
          <input id="fechaPago" type="date" class="form-control mb-3">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead><tr><th>Pedido</th><th>Monto</th><th>Fecha</th><th>Metodo</th></tr></thead>
              <tbody>
                <tr><td>301</td><td>S/ 71.00</td><td>2026-05-14</td><td>Yape</td></tr>
                <tr><td>303</td><td>S/ 29.90</td><td>2026-05-13</td><td>Efectivo</td></tr>
              </tbody>
            </table>
          </div>
        </article>
      </div>
    </section>
  </main>
  <script src="../assets/js/admin.js"></script>
</body>
</html>

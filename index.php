<?php
require_once 'config/database.php';
require_once 'model/Producto.php';
require_once 'model/Cliente.php';
require_once 'model/Pedido.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$msgPedido  = '';
$tipoPedido = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_pedido'])) {
  $nombre     = trim($_POST['nombre'] ?? '');
  $telefono   = trim($_POST['telefono'] ?? '');
  $direccion  = trim($_POST['direccion'] ?? '');
  $productoId = (int)($_POST['producto'] ?? 0);
  $cantidad   = max(1, (int)($_POST['cantidad'] ?? 1));
  $entrega    = $_POST['entrega'] ?? '';
  $obs        = trim($_POST['observaciones'] ?? '');

  if ($nombre && $telefono && $direccion && $productoId && $entrega) {
    try {
      $prod = Producto::porId($productoId);
      if ($prod) {
        $cli = Cliente::porTelefono($telefono);
        $clienteId = $cli ? $cli['id'] : Cliente::crear(['nombre' => $nombre, 'telefono' => $telefono, 'direccion' => $direccion]);
        Pedido::crear([
          'cliente_id'    => $clienteId,
          'producto_id'   => $productoId,
          'cantidad'      => $cantidad,
          'total'         => $prod['precio'] * $cantidad,
          'forma_entrega' => $entrega,
          'observaciones' => $obs,
        ]);
        $_SESSION['flash_public'] = 'ok';
      }
    } catch (Exception $e) {
      $_SESSION['flash_public'] = 'error';
    }
  } else {
    $_SESSION['flash_public'] = 'campos';
  }
  header('Location: index.php#pedido');
  exit;
}

$flashPublic = $_SESSION['flash_public'] ?? '';
unset($_SESSION['flash_public']);

try {
  $productosDB = Producto::activos();
} catch (Exception $e) {
  $productosDB = [];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SisRestaurante | Home</title>
  <meta name="description" content="Sistema web para menu y pedidos de restaurante local.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="hero-wrap">
    <nav class="navbar navbar-expand-lg">
      <div class="container py-3">
        <a class="navbar-brand brand" href="#inicio">Sabor Local</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir navegacion">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav ms-auto gap-lg-3 mt-3 mt-lg-0">
            <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
            <li class="nav-item"><a class="nav-link" href="#pedido">Pedir</a></li>
            <li class="nav-item"><a class="nav-link" href="#info">Info</a></li>
            <li class="nav-item"><a class="btn btn-admin" href="admin/login.php">Admin</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <section id="inicio" class="container py-4 py-md-5">
      <div class="row align-items-center g-4">
        <div class="col-12 col-lg-6">
          <p class="eyebrow mb-2">Restaurante local</p>
          <h1 class="display-title">Menu con sabor urbano, rapido y bien hecho.</h1>
          <p class="lead-copy mt-3">Conoce nuestros platos, realiza tu pedido y recibe en casa o recoge en tienda.</p>
          <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="#menu" class="btn btn-main">Ver menu</a>
            <a href="#pedido" class="btn btn-outline-main">Realizar pedido</a>
          </div>
        </div>
        <div class="col-12 col-lg-6">
          <div class="hero-card" role="img" aria-label="Plato principal del restaurante">
            <div class="hero-badge">Abierto hoy 11:00 AM - 11:00 PM</div>
            <h2 class="h4">Especial del dia</h2>
            <p>Combo parrillero con papas rusticas y bebida artesanal.</p>
          </div>
        </div>
      </div>
    </section>
  </header>

  <main>
    <section id="menu" class="container py-5">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div>
          <p class="eyebrow mb-1">Catalogo</p>
          <h2 class="section-title mb-0">Menu del restaurante</h2>
        </div>
        <div class="chip-wrap" role="tablist" aria-label="Categorias del menu">
          <button class="chip active" data-filter="all" type="button">Todas</button>
          <button class="chip" data-filter="hamburguesas" type="button">Hamburguesas</button>
          <button class="chip" data-filter="pizzas" type="button">Pizzas</button>
          <button class="chip" data-filter="bebidas" type="button">Bebidas</button>
          <button class="chip" data-filter="postres" type="button">Postres</button>
          <button class="chip" data-filter="almuerzos" type="button">Almuerzos</button>
          <button class="chip" data-filter="combos" type="button">Combos</button>
        </div>
      </div>
      <div id="menuGrid" class="row g-4"></div>
    </section>

    <section id="pedido" class="container py-5">
      <div class="row g-4">
        <div class="col-12 col-lg-5">
          <p class="eyebrow mb-1">Pedido rapido</p>
          <h2 class="section-title">Formulario de pedido</h2>
          <p class="mb-0">Completa tus datos para registrar el pedido.</p>
        </div>
        <div class="col-12 col-lg-7">
          <?php if ($flashPublic === 'ok'): ?>
          <div class="alert alert-success">Pedido registrado correctamente. Nos contactaremos pronto.</div>
          <?php elseif ($flashPublic === 'campos'): ?>
          <div class="alert alert-warning">Completa todos los campos obligatorios.</div>
          <?php elseif ($flashPublic === 'error'): ?>
          <div class="alert alert-danger">Ocurrio un error. Intenta nuevamente.</div>
          <?php endif; ?>
          <form id="orderForm" class="order-form" method="POST" action="index.php" novalidate>
            <input type="hidden" name="_pedido" value="1">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" required>
              </div>
              <div class="col-12">
                <label for="direccion" class="form-label">Direccion</label>
                <input type="text" id="direccion" name="direccion" class="form-control" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="producto" class="form-label">Producto</label>
                <select id="producto" name="producto" class="form-select" required></select>
              </div>
              <div class="col-12 col-md-6">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" min="1" value="1" class="form-control" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="entrega" class="form-label">Forma de entrega</label>
                <select id="entrega" name="entrega" class="form-select" required>
                  <option value="">Seleccione</option>
                  <option value="delivery">Delivery</option>
                  <option value="recojo">Recojo en local</option>
                </select>
              </div>
              <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="3" class="form-control" placeholder="Sin cebolla, sin picante, etc."></textarea>
              </div>
              <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                <button type="submit" class="btn btn-main">Enviar pedido</button>
                <button type="reset" class="btn btn-outline-main">Cancelar</button>
                <p id="formMsg" class="mb-0 ms-lg-2" aria-live="polite"></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section id="info" class="container py-5">
      <div class="row g-4">
        <div class="col-12 col-md-4">
          <article class="info-card">
            <h3>Informacion</h3>
            <p>Restaurante familiar con cocina rapida y opciones de almuerzo diario.</p>
          </article>
        </div>
        <div class="col-12 col-md-4">
          <article class="info-card">
            <h3>Horarios</h3>
            <p>Lunes a domingo: 11:00 AM - 11:00 PM</p>
          </article>
        </div>
        <div class="col-12 col-md-4">
          <article class="info-card">
            <h3>Ubicacion</h3>
            <p>Av. Principal 123, Centro de la ciudad.</p>
          </article>
        </div>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
  window.menuProducts = <?= json_encode(array_map(fn($p) => [
      'id'          => (int)$p['id'],
      'category'    => $p['categoria_slug'],
      'name'        => $p['nombre'],
      'description' => $p['descripcion'],
      'price'       => (float)$p['precio'],
      'image'       => $p['imagen'] ?? '',
  ], $productosDB), JSON_UNESCAPED_UNICODE) ?>;
  </script>
  <script src="assets/js/app.js"></script>
</body>
</html>

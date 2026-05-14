<?php /* Expects: $pageTitle (string), $activePage (string) */ ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?> | SisRestaurante Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="is-admin">
<div class="admin-wrap">

  <aside class="admin-side" role="complementary" aria-label="Panel lateral">
    <a class="admin-brand" href="../index.php" title="Ir al home publico">Sabor<br>Local</a>
    <?php
    $navLinks = [
      'dashboard'  => ['bi' => 'speedometer2',  'label' => 'Dashboard',  'href' => 'dashboard.php'],
      'pedidos'    => ['bi' => 'bag-check',      'label' => 'Pedidos',    'href' => 'pedidos.php'],
      'clientes'   => ['bi' => 'people',         'label' => 'Clientes',   'href' => 'clientes.php'],
      'productos'  => ['bi' => 'box-seam',       'label' => 'Productos',  'href' => 'productos.php'],
      'categorias' => ['bi' => 'tags',           'label' => 'Categorias', 'href' => 'categorias.php'],
      'empleados'  => ['bi' => 'person-badge',   'label' => 'Empleados',  'href' => 'empleados.php'],
      'pagos'      => ['bi' => 'cash-stack',     'label' => 'Pagos',      'href' => 'pagos.php'],
    ];
    foreach ($navLinks as $key => $nav):
      $cls = ($activePage ?? '') === $key ? ' active' : '';
    ?>
    <a href="<?= $nav['href'] ?>" class="side-link<?= $cls ?>">
      <i class="bi bi-<?= $nav['bi'] ?>"></i><span><?= $nav['label'] ?></span>
    </a>
    <?php endforeach; ?>
    <a href="logout.php" class="side-link side-logout"><i class="bi bi-box-arrow-left"></i><span>Salir</span></a>
  </aside>

  <div class="admin-main">
    <div class="admin-topbar">
      <h1 class="admin-page-title"><?= htmlspecialchars($pageTitle) ?></h1>
      <small class="text-muted">Sesion: <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Admin') ?></small>
    </div>

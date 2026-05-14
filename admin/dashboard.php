<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Producto.php';
require_once '../model/Cliente.php';
require_once '../model/Empleado.php';
require_once '../model/Pedido.php';
require_once '../model/Pago.php';
requireAdmin();

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
$flash = getFlash();

$stats = [
    'productos'  => Producto::contar(),
    'pendientes' => Pedido::contarPorEstado('pendiente'),
    'entregados' => Pedido::contarPorEstado('entregado'),
    'clientes'   => Cliente::contar(),
    'empleados'  => Empleado::contar(),
    'pagos'      => Pago::totalRecibido(),
];
$recientes = Pedido::todos('', '', 10);

include '../views/layouts/admin_head.php';
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-flash alert-dismissible">
  <?= htmlspecialchars($flash['msg']) ?>
  <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['label' => 'Productos activos', 'val' => $stats['productos'],                    'link' => 'productos.php'],
    ['label' => 'Pedidos pendientes','val' => $stats['pendientes'],                   'link' => 'pedidos.php?estado=pendiente'],
    ['label' => 'Entregados',        'val' => $stats['entregados'],                   'link' => 'pedidos.php?estado=entregado'],
    ['label' => 'Clientes activos',  'val' => $stats['clientes'],                     'link' => 'clientes.php'],
    ['label' => 'Empleados',         'val' => $stats['empleados'],                    'link' => 'empleados.php'],
    ['label' => 'Pagos S/',          'val' => number_format($stats['pagos'], 2),      'link' => 'pagos.php'],
  ];
  foreach ($cards as $card): ?>
  <div class="col-6 col-lg-2">
    <a href="<?= $card['link'] ?>" class="text-decoration-none">
      <div class="stat-card">
        <p class="small text-muted mb-1"><?= $card['label'] ?></p>
        <p class="stat-val"><?= $card['val'] ?></p>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<div class="admin-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Pedidos recientes</h2>
    <a href="pedidos.php" class="btn btn-main btn-sm">Ver todos</a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>#</th><th>Cliente</th><th>Producto</th><th>Total</th><th>Estado</th><th>Fecha</th></tr>
      </thead>
      <tbody>
        <?php foreach ($recientes as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['cliente_nombre']) ?></td>
          <td><?= htmlspecialchars($p['producto_nombre']) ?></td>
          <td>S/ <?= number_format($p['total'], 2) ?></td>
          <td><span class="badge-<?= $p['estado'] ?>"><?= ucfirst(str_replace('_', ' ', $p['estado'])) ?></span></td>
          <td><?= date('d/m/Y H:i', strtotime($p['fecha'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$recientes): ?>
        <tr><td colspan="6" class="text-center text-muted py-3">Sin pedidos aun.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

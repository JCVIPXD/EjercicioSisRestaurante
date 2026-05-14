<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Pago.php';
require_once '../model/Pedido.php';
requireAdmin();

$pageTitle  = 'Pagos';
$activePage = 'pagos';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidoId = (int)($_POST['pedido_id'] ?? 0);
    $metodo   = $_POST['metodo'] ?? '';
    $monto    = (float)($_POST['monto'] ?? 0);
    try {
        if ($pedidoId && $monto > 0 && in_array($metodo, ['efectivo','yape','plin','tarjeta'], true)) {
            if (!Pago::existePorPedido($pedidoId)) {
                Pago::crear(['pedido_id' => $pedidoId, 'monto' => $monto, 'metodo' => $metodo]);
                setFlash('ok', 'Pago registrado correctamente.');
            } else {
                setFlash('error', 'Este pedido ya tiene un pago registrado.');
            }
        } else {
            setFlash('error', 'Datos incompletos o invalidos.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Error al registrar el pago.');
    }
    header('Location: pagos.php');
    exit;
}

$fecha       = $_GET['fecha'] ?? '';
$pagos       = Pago::todos($fecha);
$sinPago     = Pedido::sinPago();
$totalFiltro = array_sum(array_column($pagos, 'monto'));

include '../views/layouts/admin_head.php';
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-flash alert-dismissible">
  <?= htmlspecialchars($flash['msg']) ?>
  <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-12 col-lg-4">
    <div class="admin-card">
      <h2 class="h5 mb-3">Registrar pago</h2>
      <form method="POST" class="row g-3">
        <div class="col-12">
          <label class="form-label">Pedido sin pago <span class="text-danger">*</span></label>
          <select name="pedido_id" class="form-select" required>
            <option value="">Seleccione pedido</option>
            <?php foreach ($sinPago as $sp): ?>
            <option value="<?= $sp['id'] ?>">#<?= $sp['id'] ?> — <?= htmlspecialchars($sp['cliente_nombre']) ?> | <?= htmlspecialchars($sp['producto_nombre']) ?> | S/ <?= number_format($sp['total'], 2) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 col-md-6">
          <label class="form-label">Monto <span class="text-danger">*</span></label>
          <input type="number" name="monto" class="form-control" step="0.01" min="0.01" required>
        </div>
        <div class="col-12 col-md-6">
          <label class="form-label">Metodo <span class="text-danger">*</span></label>
          <select name="metodo" class="form-select" required>
            <option value="efectivo">Efectivo</option>
            <option value="yape">Yape</option>
            <option value="plin">Plin</option>
            <option value="tarjeta">Tarjeta</option>
          </select>
        </div>
        <div class="col-12">
          <button class="btn btn-main w-100" type="submit">Registrar pago</button>
        </div>
      </form>
    </div>
  </div>

  <div class="col-12 col-lg-8">
    <div class="admin-card">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="h5 mb-0">Historial de pagos</h2>
        <strong class="text-success">Total: S/ <?= number_format($totalFiltro, 2) ?></strong>
      </div>
      <form class="d-flex gap-2 mb-3" method="GET">
        <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($fecha) ?>">
        <button class="btn btn-main" type="submit">Filtrar</button>
        <?php if ($fecha): ?><a href="pagos.php" class="btn btn-outline-main">Limpiar</a><?php endif; ?>
      </form>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr><th>#Pago</th><th>#Pedido</th><th>Cliente</th><th>Monto</th><th>Metodo</th><th>Fecha</th></tr>
          </thead>
          <tbody>
            <?php foreach ($pagos as $pago): ?>
            <tr>
              <td><?= $pago['id'] ?></td>
              <td><?= $pago['num_pedido'] ?></td>
              <td><?= htmlspecialchars($pago['cliente_nombre']) ?></td>
              <td>S/ <?= number_format($pago['monto'], 2) ?></td>
              <td><?= ucfirst($pago['metodo']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($pago['fecha'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$pagos): ?>
            <tr><td colspan="6" class="text-center text-muted py-3">Sin pagos<?= $fecha ? ' en esta fecha' : '' ?>.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

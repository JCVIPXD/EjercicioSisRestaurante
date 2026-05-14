<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Pedido.php';
requireAdmin();

$pageTitle  = 'Pedidos';
$activePage = 'pedidos';
$flash = getFlash();

const ESTADOS = ['pendiente', 'en_preparacion', 'enviado', 'entregado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['_accion'] ?? '';
    $id     = (int)($_POST['_id'] ?? 0);
    try {
        if ($accion === 'estado' && $id && in_array($_POST['estado'] ?? '', ESTADOS, true)) {
            Pedido::actualizarEstado($id, $_POST['estado']);
            setFlash('ok', 'Estado actualizado.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Error al actualizar el estado.');
    }
    header('Location: pedidos.php');
    exit;
}

$buscar  = trim($_GET['buscar'] ?? '');
$estado  = in_array($_GET['estado'] ?? '', ESTADOS, true) ? $_GET['estado'] : '';
$pedidos = Pedido::todos($buscar, $estado);

include '../views/layouts/admin_head.php';
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-flash alert-dismissible">
  <?= htmlspecialchars($flash['msg']) ?>
  <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="admin-card">
  <form class="row g-2 mb-3 align-items-end" method="GET">
    <div class="col-12 col-md-5">
      <label class="form-label">Buscar</label>
      <input type="search" name="buscar" class="form-control" value="<?= htmlspecialchars($buscar) ?>" placeholder="Cliente o producto...">
    </div>
    <div class="col-12 col-md-4">
      <label class="form-label">Estado</label>
      <select name="estado" class="form-select">
        <option value="">Todos</option>
        <?php foreach (ESTADOS as $e): ?>
        <option value="<?= $e ?>" <?= $estado === $e ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $e)) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-auto d-flex gap-2">
      <button class="btn btn-main" type="submit">Filtrar</button>
      <?php if ($buscar || $estado): ?><a href="pedidos.php" class="btn btn-outline-main">Limpiar</a><?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>#</th><th>Cliente</th><th>Producto</th><th>Cant.</th><th>Total</th><th>Entrega</th><th>Estado</th><th>Fecha</th><th>Cambiar estado</th></tr>
      </thead>
      <tbody>
        <?php foreach ($pedidos as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['cliente_nombre']) ?></td>
          <td><?= htmlspecialchars($p['producto_nombre']) ?></td>
          <td><?= $p['cantidad'] ?></td>
          <td>S/ <?= number_format($p['total'], 2) ?></td>
          <td><?= $p['forma_entrega'] === 'delivery' ? 'Delivery' : 'Recojo' ?></td>
          <td><span class="badge-<?= $p['estado'] ?>"><?= ucfirst(str_replace('_', ' ', $p['estado'])) ?></span></td>
          <td><?= date('d/m/Y H:i', strtotime($p['fecha'])) ?></td>
          <td>
            <form method="POST" class="d-flex gap-1 align-items-center">
              <input type="hidden" name="_accion" value="estado">
              <input type="hidden" name="_id" value="<?= $p['id'] ?>">
              <select name="estado" class="form-select form-select-sm" style="width:auto">
                <?php foreach (ESTADOS as $e): ?>
                <option value="<?= $e ?>" <?= $p['estado'] === $e ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $e)) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-sm btn-main" type="submit">OK</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$pedidos): ?>
        <tr><td colspan="9" class="text-center text-muted py-3">Sin pedidos.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

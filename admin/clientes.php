<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Cliente.php';
requireAdmin();

$pageTitle  = 'Clientes';
$activePage = 'clientes';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['_accion'] ?? '';
    $id     = (int)($_POST['_id'] ?? 0);
    $d = [
        'nombre'    => trim($_POST['nombre'] ?? ''),
        'telefono'  => trim($_POST['telefono'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
    ];
    try {
        if ($accion === 'crear' && $d['nombre']) {
            Cliente::crear($d);
            setFlash('ok', 'Cliente creado correctamente.');
        } elseif ($accion === 'actualizar' && $id && $d['nombre']) {
            Cliente::actualizar($id, $d);
            setFlash('ok', 'Cliente actualizado.');
        } elseif ($accion === 'toggle' && $id) {
            Cliente::toggleActivo($id);
            setFlash('ok', 'Estado del cliente cambiado.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Error al procesar la solicitud.');
    }
    header('Location: clientes.php');
    exit;
}

$editId   = (int)($_GET['editar'] ?? 0);
$editItem = $editId ? Cliente::porId($editId) : null;
$buscar   = trim($_GET['buscar'] ?? '');
$clientes = Cliente::todos($buscar);

include '../views/layouts/admin_head.php';
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-flash alert-dismissible">
  <?= htmlspecialchars($flash['msg']) ?>
  <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="admin-card">
  <h2 class="h5 mb-3"><?= $editItem ? 'Editar cliente' : 'Nuevo cliente' ?></h2>
  <form method="POST" class="row g-3">
    <input type="hidden" name="_accion" value="<?= $editItem ? 'actualizar' : 'crear' ?>">
    <?php if ($editItem): ?><input type="hidden" name="_id" value="<?= $editItem['id'] ?>"><?php endif; ?>
    <div class="col-12 col-md-4">
      <label class="form-label">Nombre <span class="text-danger">*</span></label>
      <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($editItem['nombre'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-3">
      <label class="form-label">Telefono <span class="text-danger">*</span></label>
      <input type="tel" name="telefono" class="form-control" required value="<?= htmlspecialchars($editItem['telefono'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-5">
      <label class="form-label">Direccion <span class="text-danger">*</span></label>
      <input type="text" name="direccion" class="form-control" required value="<?= htmlspecialchars($editItem['direccion'] ?? '') ?>">
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-main" type="submit">Guardar</button>
      <?php if ($editItem): ?><a href="clientes.php" class="btn btn-outline-main">Cancelar</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="admin-card">
  <form class="d-flex gap-2 mb-3" method="GET">
    <input type="search" name="buscar" class="form-control" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar por nombre o telefono...">
    <button class="btn btn-main" type="submit">Buscar</button>
    <?php if ($buscar): ?><a href="clientes.php" class="btn btn-outline-main">Limpiar</a><?php endif; ?>
  </form>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>#</th><th>Nombre</th><th>Telefono</th><th>Direccion</th><th>Estado</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $c): ?>
        <tr>
          <td><?= $c['id'] ?></td>
          <td><?= htmlspecialchars($c['nombre']) ?></td>
          <td><?= htmlspecialchars($c['telefono']) ?></td>
          <td><?= htmlspecialchars($c['direccion']) ?></td>
          <td><span class="badge <?= $c['activo'] ? 'bg-success' : 'bg-secondary' ?>"><?= $c['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
          <td class="d-flex gap-1 flex-wrap">
            <a href="?editar=<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form method="POST" class="d-inline">
              <input type="hidden" name="_accion" value="toggle">
              <input type="hidden" name="_id" value="<?= $c['id'] ?>">
              <button class="btn btn-sm <?= $c['activo'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" type="submit">
                <?= $c['activo'] ? 'Desactivar' : 'Activar' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$clientes): ?>
        <tr><td colspan="6" class="text-center text-muted py-3">Sin registros.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

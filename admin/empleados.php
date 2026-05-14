<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Empleado.php';
requireAdmin();

$pageTitle  = 'Empleados';
$activePage = 'empleados';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['_accion'] ?? '';
    $id     = (int)($_POST['_id'] ?? 0);
    $d = [
        'nombre'   => trim($_POST['nombre'] ?? ''),
        'rol'      => trim($_POST['rol'] ?? ''),
        'turno'    => $_POST['turno'] ?? 'tarde',
        'telefono' => trim($_POST['telefono'] ?? ''),
    ];
    try {
        if ($accion === 'crear' && $d['nombre']) {
            Empleado::crear($d);
            setFlash('ok', 'Empleado registrado.');
        } elseif ($accion === 'actualizar' && $id && $d['nombre']) {
            Empleado::actualizar($id, $d);
            setFlash('ok', 'Empleado actualizado.');
        } elseif ($accion === 'eliminar' && $id) {
            Empleado::eliminar($id);
            setFlash('ok', 'Empleado desactivado.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Error al procesar la solicitud.');
    }
    header('Location: empleados.php');
    exit;
}

$editId    = (int)($_GET['editar'] ?? 0);
$editItem  = $editId ? Empleado::porId($editId) : null;
$buscar    = trim($_GET['buscar'] ?? '');
$empleados = Empleado::todos($buscar);

include '../views/layouts/admin_head.php';
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-flash alert-dismissible">
  <?= htmlspecialchars($flash['msg']) ?>
  <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="admin-card">
  <h2 class="h5 mb-3"><?= $editItem ? 'Editar empleado' : 'Nuevo empleado' ?></h2>
  <form method="POST" class="row g-3">
    <input type="hidden" name="_accion" value="<?= $editItem ? 'actualizar' : 'crear' ?>">
    <?php if ($editItem): ?><input type="hidden" name="_id" value="<?= $editItem['id'] ?>"><?php endif; ?>
    <div class="col-12 col-md-4">
      <label class="form-label">Nombre <span class="text-danger">*</span></label>
      <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($editItem['nombre'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-3">
      <label class="form-label">Rol</label>
      <input type="text" name="rol" class="form-control" placeholder="Cocinero, Cajero..." value="<?= htmlspecialchars($editItem['rol'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-2">
      <label class="form-label">Turno</label>
      <select name="turno" class="form-select">
        <?php foreach (['manana', 'tarde', 'noche'] as $t): ?>
        <option value="<?= $t ?>" <?= ($editItem['turno'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-3">
      <label class="form-label">Telefono</label>
      <input type="tel" name="telefono" class="form-control" value="<?= htmlspecialchars($editItem['telefono'] ?? '') ?>">
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-main" type="submit">Guardar</button>
      <?php if ($editItem): ?><a href="empleados.php" class="btn btn-outline-main">Cancelar</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="admin-card">
  <form class="d-flex gap-2 mb-3" method="GET">
    <input type="search" name="buscar" class="form-control" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar por nombre o rol...">
    <button class="btn btn-main" type="submit">Buscar</button>
    <?php if ($buscar): ?><a href="empleados.php" class="btn btn-outline-main">Limpiar</a><?php endif; ?>
  </form>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>#</th><th>Nombre</th><th>Rol</th><th>Turno</th><th>Telefono</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach ($empleados as $e): ?>
        <tr>
          <td><?= $e['id'] ?></td>
          <td><?= htmlspecialchars($e['nombre']) ?></td>
          <td><?= htmlspecialchars($e['rol']) ?></td>
          <td><?= ucfirst($e['turno']) ?></td>
          <td><?= htmlspecialchars($e['telefono'] ?? '-') ?></td>
          <td class="d-flex gap-1">
            <a href="?editar=<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar empleado?')">
              <input type="hidden" name="_accion" value="eliminar">
              <input type="hidden" name="_id" value="<?= $e['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Desactivar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$empleados): ?>
        <tr><td colspan="6" class="text-center text-muted py-3">Sin empleados activos.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

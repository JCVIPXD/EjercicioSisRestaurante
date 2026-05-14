<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Producto.php';
require_once '../model/Categoria.php';
requireAdmin();

$pageTitle  = 'Productos';
$activePage = 'productos';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['_accion'] ?? '';
    $id     = (int)($_POST['_id'] ?? 0);
    $d = [
        'categoria_id' => (int)($_POST['categoria_id'] ?? 0),
        'nombre'       => trim($_POST['nombre'] ?? ''),
        'descripcion'  => trim($_POST['descripcion'] ?? ''),
        'precio'       => (float)($_POST['precio'] ?? 0),
        'imagen'       => trim($_POST['imagen'] ?? '') ?: null,
    ];
    try {
        if ($accion === 'crear' && $d['nombre'] && $d['categoria_id']) {
            Producto::crear($d);
            setFlash('ok', 'Producto creado.');
        } elseif ($accion === 'actualizar' && $id && $d['nombre']) {
            Producto::actualizar($id, $d);
            setFlash('ok', 'Producto actualizado.');
        } elseif ($accion === 'toggle' && $id) {
            Producto::toggleActivo($id);
            setFlash('ok', 'Estado del producto cambiado.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Error al procesar la solicitud.');
    }
    header('Location: productos.php');
    exit;
}

$editId      = (int)($_GET['editar'] ?? 0);
$editItem    = $editId ? Producto::porId($editId) : null;
$buscar      = trim($_GET['buscar'] ?? '');
$productos   = Producto::todos($buscar);
$categorias  = Categoria::todas();

include '../views/layouts/admin_head.php';
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-flash alert-dismissible">
  <?= htmlspecialchars($flash['msg']) ?>
  <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="admin-card">
  <h2 class="h5 mb-3"><?= $editItem ? 'Editar producto' : 'Nuevo producto' ?></h2>
  <form method="POST" class="row g-3">
    <input type="hidden" name="_accion" value="<?= $editItem ? 'actualizar' : 'crear' ?>">
    <?php if ($editItem): ?><input type="hidden" name="_id" value="<?= $editItem['id'] ?>"><?php endif; ?>
    <div class="col-12 col-md-4">
      <label class="form-label">Nombre <span class="text-danger">*</span></label>
      <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($editItem['nombre'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-3">
      <label class="form-label">Categoria <span class="text-danger">*</span></label>
      <select name="categoria_id" class="form-select" required>
        <option value="">Seleccione</option>
        <?php foreach ($categorias as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= ($editItem['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['nombre']) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-2">
      <label class="form-label">Precio <span class="text-danger">*</span></label>
      <input type="number" name="precio" class="form-control" step="0.01" min="0" required value="<?= $editItem['precio'] ?? '' ?>">
    </div>
    <div class="col-12 col-md-3">
      <label class="form-label">URL imagen</label>
      <input type="url" name="imagen" class="form-control" value="<?= htmlspecialchars($editItem['imagen'] ?? '') ?>">
    </div>
    <div class="col-12">
      <label class="form-label">Descripcion</label>
      <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($editItem['descripcion'] ?? '') ?>">
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-main" type="submit">Guardar</button>
      <?php if ($editItem): ?><a href="productos.php" class="btn btn-outline-main">Cancelar</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="admin-card">
  <form class="d-flex gap-2 mb-3" method="GET">
    <input type="search" name="buscar" class="form-control" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar por nombre o categoria...">
    <button class="btn btn-main" type="submit">Buscar</button>
    <?php if ($buscar): ?><a href="productos.php" class="btn btn-outline-main">Limpiar</a><?php endif; ?>
  </form>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>#</th><th>Nombre</th><th>Categoria</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>
          <td>S/ <?= number_format($p['precio'], 2) ?></td>
          <td><span class="badge <?= $p['activo'] ? 'bg-success' : 'bg-secondary' ?>"><?= $p['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
          <td class="d-flex gap-1 flex-wrap">
            <a href="?editar=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form method="POST" class="d-inline">
              <input type="hidden" name="_accion" value="toggle">
              <input type="hidden" name="_id" value="<?= $p['id'] ?>">
              <button class="btn btn-sm <?= $p['activo'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" type="submit">
                <?= $p['activo'] ? 'Desactivar' : 'Activar' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$productos): ?>
        <tr><td colspan="6" class="text-center text-muted py-3">Sin registros.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

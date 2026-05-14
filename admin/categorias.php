<?php
require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../model/Categoria.php';
requireAdmin();

$pageTitle  = 'Categorias';
$activePage = 'categorias';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['_accion'] ?? '';
    $id     = (int)($_POST['_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $slug   = strtolower(preg_replace('/\s+/', '-', trim($_POST['slug'] ?? '')));
    try {
        if ($accion === 'crear' && $nombre) {
            Categoria::crear(['nombre' => $nombre, 'slug' => $slug ?: strtolower($nombre)]);
            setFlash('ok', 'Categoria creada.');
        } elseif ($accion === 'actualizar' && $id && $nombre) {
            Categoria::actualizar($id, ['nombre' => $nombre, 'slug' => $slug ?: strtolower($nombre)]);
            setFlash('ok', 'Categoria actualizada.');
        } elseif ($accion === 'eliminar' && $id) {
            Categoria::eliminar($id);
            setFlash('ok', 'Categoria eliminada.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'No se puede eliminar: tiene productos asociados.');
    }
    header('Location: categorias.php');
    exit;
}

$editId      = (int)($_GET['editar'] ?? 0);
$editItem    = $editId ? Categoria::porId($editId) : null;
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
  <h2 class="h5 mb-3"><?= $editItem ? 'Editar categoria' : 'Nueva categoria' ?></h2>
  <form method="POST" class="row g-3">
    <input type="hidden" name="_accion" value="<?= $editItem ? 'actualizar' : 'crear' ?>">
    <?php if ($editItem): ?><input type="hidden" name="_id" value="<?= $editItem['id'] ?>"><?php endif; ?>
    <div class="col-12 col-md-5">
      <label class="form-label">Nombre <span class="text-danger">*</span></label>
      <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($editItem['nombre'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-4">
      <label class="form-label">Slug <span class="text-muted small">(auto si se deja vacio)</span></label>
      <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($editItem['slug'] ?? '') ?>" placeholder="ej: hamburguesas">
    </div>
    <div class="col-12 d-flex gap-2 align-items-end">
      <button class="btn btn-main" type="submit">Guardar</button>
      <?php if ($editItem): ?><a href="categorias.php" class="btn btn-outline-main">Cancelar</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="admin-card">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>#</th><th>Nombre</th><th>Slug</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach ($categorias as $cat): ?>
        <tr>
          <td><?= $cat['id'] ?></td>
          <td><?= htmlspecialchars($cat['nombre']) ?></td>
          <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
          <td class="d-flex gap-1">
            <a href="?editar=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar categoria?')">
              <input type="hidden" name="_accion" value="eliminar">
              <input type="hidden" name="_id" value="<?= $cat['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$categorias): ?>
        <tr><td colspan="4" class="text-center text-muted py-3">Sin categorias.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../views/layouts/admin_foot.php'; ?>

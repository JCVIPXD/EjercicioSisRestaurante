<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | SisRestaurante</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <main class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <section class="order-form w-100" style="max-width: 520px;">
      <p class="eyebrow mb-1">Panel interno</p>
      <h1 class="section-title mb-3">Ingreso administrador</h1>
      <form action="dashboard.php" method="get" class="row g-3" novalidate>
        <div class="col-12">
          <label for="correo" class="form-label">Correo</label>
          <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="col-12">
          <label for="clave" class="form-label">Clave</label>
          <input type="password" class="form-control" id="clave" name="clave" required>
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
          <button class="btn btn-main" type="submit">Ingresar</button>
          <button class="btn btn-outline-main" type="reset">Cancelar</button>
          <a class="btn btn-outline-main" href="../index.php">Volver al inicio</a>
        </div>
      </form>
    </section>
  </main>
</body>
</html>

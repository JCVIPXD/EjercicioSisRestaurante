<?php
require_once __DIR__ . '/../config/database.php';

class Producto
{
    public static function todos(string $buscar = ''): array
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p JOIN categorias c ON c.id = p.categoria_id";
        $db = getDB();
        if ($buscar !== '') {
            $stmt = $db->prepare($sql . " WHERE p.nombre LIKE ? OR c.nombre LIKE ? ORDER BY p.id DESC");
            $like = "%$buscar%";
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $db->query($sql . " ORDER BY p.id DESC");
        }
        return $stmt->fetchAll();
    }

    public static function activos(): array
    {
        $stmt = getDB()->query(
            "SELECT p.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
             FROM productos p JOIN categorias c ON c.id = p.categoria_id
             WHERE p.activo = 1
             ORDER BY c.nombre, p.nombre"
        );
        return $stmt->fetchAll();
    }

    public static function porId(int $id): array|false
    {
        $stmt = getDB()->prepare("SELECT * FROM productos WHERE id=? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function crear(array $d): void
    {
        getDB()->prepare(
            "INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen) VALUES (?,?,?,?,?)"
        )->execute([$d['categoria_id'], $d['nombre'], $d['descripcion'], $d['precio'], $d['imagen'] ?? null]);
    }

    public static function actualizar(int $id, array $d): void
    {
        getDB()->prepare(
            "UPDATE productos SET categoria_id=?, nombre=?, descripcion=?, precio=?, imagen=? WHERE id=?"
        )->execute([$d['categoria_id'], $d['nombre'], $d['descripcion'], $d['precio'], $d['imagen'] ?? null, $id]);
    }

    public static function toggleActivo(int $id): void
    {
        getDB()->prepare("UPDATE productos SET activo = NOT activo WHERE id=?")->execute([$id]);
    }

    public static function contar(): int
    {
        return (int) getDB()->query("SELECT COUNT(*) FROM productos WHERE activo=1")->fetchColumn();
    }
}

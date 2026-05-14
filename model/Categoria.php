<?php
require_once __DIR__ . '/../config/database.php';

class Categoria
{
    public static function todas(): array
    {
        return getDB()->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
    }

    public static function porId(int $id): array|false
    {
        $stmt = getDB()->prepare("SELECT * FROM categorias WHERE id=? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function crear(array $d): void
    {
        getDB()->prepare(
            "INSERT INTO categorias (nombre, slug) VALUES (?, ?)"
        )->execute([$d['nombre'], $d['slug']]);
    }

    public static function actualizar(int $id, array $d): void
    {
        getDB()->prepare(
            "UPDATE categorias SET nombre=?, slug=? WHERE id=?"
        )->execute([$d['nombre'], $d['slug'], $id]);
    }

    public static function eliminar(int $id): void
    {
        getDB()->prepare("DELETE FROM categorias WHERE id=?")->execute([$id]);
    }
}

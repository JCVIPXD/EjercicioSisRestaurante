<?php
require_once __DIR__ . '/../config/database.php';

class Cliente
{
    public static function todos(string $buscar = ''): array
    {
        $db = getDB();
        if ($buscar !== '') {
            $stmt = $db->prepare(
                "SELECT * FROM clientes WHERE nombre LIKE ? OR telefono LIKE ? ORDER BY id DESC"
            );
            $like = "%$buscar%";
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $db->query("SELECT * FROM clientes ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    }

    public static function porId(int $id): array|false
    {
        $stmt = getDB()->prepare("SELECT * FROM clientes WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function porTelefono(string $tel): array|false
    {
        $stmt = getDB()->prepare("SELECT * FROM clientes WHERE telefono = ? LIMIT 1");
        $stmt->execute([$tel]);
        return $stmt->fetch();
    }

    public static function crear(array $d): int
    {
        $db = getDB();
        $db->prepare(
            "INSERT INTO clientes (nombre, telefono, direccion) VALUES (?, ?, ?)"
        )->execute([$d['nombre'], $d['telefono'], $d['direccion']]);
        return (int) $db->lastInsertId();
    }

    public static function actualizar(int $id, array $d): void
    {
        getDB()->prepare(
            "UPDATE clientes SET nombre=?, telefono=?, direccion=? WHERE id=?"
        )->execute([$d['nombre'], $d['telefono'], $d['direccion'], $id]);
    }

    public static function toggleActivo(int $id): void
    {
        getDB()->prepare("UPDATE clientes SET activo = NOT activo WHERE id=?")->execute([$id]);
    }

    public static function contar(): int
    {
        return (int) getDB()->query("SELECT COUNT(*) FROM clientes WHERE activo=1")->fetchColumn();
    }
}

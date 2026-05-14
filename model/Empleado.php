<?php
require_once __DIR__ . '/../config/database.php';

class Empleado
{
    public static function todos(string $buscar = ''): array
    {
        $db  = getDB();
        $sql = "SELECT * FROM empleados WHERE activo=1";
        if ($buscar !== '') {
            $stmt = $db->prepare($sql . " AND (nombre LIKE ? OR rol LIKE ?) ORDER BY id DESC");
            $like = "%$buscar%";
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $db->query($sql . " ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    }

    public static function porId(int $id): array|false
    {
        $stmt = getDB()->prepare("SELECT * FROM empleados WHERE id=? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function crear(array $d): void
    {
        getDB()->prepare(
            "INSERT INTO empleados (nombre, rol, turno, telefono) VALUES (?,?,?,?)"
        )->execute([$d['nombre'], $d['rol'], $d['turno'], $d['telefono']]);
    }

    public static function actualizar(int $id, array $d): void
    {
        getDB()->prepare(
            "UPDATE empleados SET nombre=?, rol=?, turno=?, telefono=? WHERE id=?"
        )->execute([$d['nombre'], $d['rol'], $d['turno'], $d['telefono'], $id]);
    }

    public static function eliminar(int $id): void
    {
        getDB()->prepare("UPDATE empleados SET activo=0 WHERE id=?")->execute([$id]);
    }

    public static function contar(): int
    {
        return (int) getDB()->query("SELECT COUNT(*) FROM empleados WHERE activo=1")->fetchColumn();
    }
}

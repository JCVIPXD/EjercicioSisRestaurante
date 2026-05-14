<?php
require_once __DIR__ . '/../config/database.php';

class Pedido
{
    public static function todos(string $buscar = '', string $estado = '', int $limite = 0): array
    {
        $sql    = "SELECT pe.*, cl.nombre AS cliente_nombre, pr.nombre AS producto_nombre
                   FROM pedidos pe
                   JOIN clientes cl ON cl.id = pe.cliente_id
                   JOIN productos pr ON pr.id = pe.producto_id
                   WHERE 1=1";
        $params = [];
        if ($estado !== '') {
            $sql .= " AND pe.estado = ?";
            $params[] = $estado;
        }
        if ($buscar !== '') {
            $sql .= " AND (cl.nombre LIKE ? OR pr.nombre LIKE ?)";
            $like     = "%$buscar%";
            $params[] = $like;
            $params[] = $like;
        }
        $sql .= " ORDER BY pe.fecha DESC";
        if ($limite > 0) {
            $sql .= " LIMIT " . (int) $limite;
        }
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function porId(int $id): array|false
    {
        $stmt = getDB()->prepare(
            "SELECT pe.*, cl.nombre AS cliente_nombre, pr.nombre AS producto_nombre
             FROM pedidos pe
             JOIN clientes cl ON cl.id = pe.cliente_id
             JOIN productos pr ON pr.id = pe.producto_id
             WHERE pe.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function crear(array $d): int
    {
        $db = getDB();
        $db->prepare(
            "INSERT INTO pedidos (cliente_id, producto_id, cantidad, total, forma_entrega, observaciones)
             VALUES (?,?,?,?,?,?)"
        )->execute([
            $d['cliente_id'], $d['producto_id'], $d['cantidad'],
            $d['total'], $d['forma_entrega'], $d['observaciones'] ?? null,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function actualizarEstado(int $id, string $estado): void
    {
        getDB()->prepare("UPDATE pedidos SET estado=? WHERE id=?")->execute([$estado, $id]);
    }

    public static function contarPorEstado(string $estado): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(*) FROM pedidos WHERE estado=?");
        $stmt->execute([$estado]);
        return (int) $stmt->fetchColumn();
    }

    public static function sinPago(): array
    {
        $stmt = getDB()->query(
            "SELECT pe.id, pe.total, pe.estado, cl.nombre AS cliente_nombre, pr.nombre AS producto_nombre
             FROM pedidos pe
             JOIN clientes cl ON cl.id = pe.cliente_id
             JOIN productos pr ON pr.id = pe.producto_id
             WHERE pe.id NOT IN (SELECT pedido_id FROM pagos)
             ORDER BY pe.fecha DESC"
        );
        return $stmt->fetchAll();
    }
}

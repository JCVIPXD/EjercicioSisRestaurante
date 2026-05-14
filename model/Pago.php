<?php
require_once __DIR__ . '/../config/database.php';

class Pago
{
    public static function todos(string $fecha = ''): array
    {
        $sql    = "SELECT pa.*, pe.id AS num_pedido, cl.nombre AS cliente_nombre
                   FROM pagos pa
                   JOIN pedidos pe ON pe.id = pa.pedido_id
                   JOIN clientes cl ON cl.id = pe.cliente_id
                   WHERE 1=1";
        $params = [];
        if ($fecha !== '') {
            $sql .= " AND DATE(pa.fecha) = ?";
            $params[] = $fecha;
        }
        $sql .= " ORDER BY pa.fecha DESC";
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function crear(array $d): void
    {
        getDB()->prepare(
            "INSERT INTO pagos (pedido_id, monto, metodo) VALUES (?,?,?)"
        )->execute([$d['pedido_id'], $d['monto'], $d['metodo']]);
    }

    public static function existePorPedido(int $pedidoId): bool
    {
        $stmt = getDB()->prepare("SELECT COUNT(*) FROM pagos WHERE pedido_id=?");
        $stmt->execute([$pedidoId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function totalRecibido(): float
    {
        return (float) getDB()->query("SELECT COALESCE(SUM(monto),0) FROM pagos")->fetchColumn();
    }
}

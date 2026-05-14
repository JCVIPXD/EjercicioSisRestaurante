<?php
require_once __DIR__ . '/../config/database.php';

class Usuario
{
    public static function porCorreo(string $correo): array|false
    {
        $stmt = getDB()->prepare(
            'SELECT * FROM usuarios WHERE correo = ? AND activo = 1 LIMIT 1'
        );
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }
}

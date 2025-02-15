<?php

namespace App\Enums;

enum Requerimientos: string
{
    case ACEPTADO = 'ACEPTADO';
    case PENDIENTE = 'PENDIENTE';
    case RECHAZADO = 'RECHAZADO';
    

    public function label(): string
    {
        return match ($this) {
            self::ACEPTADO => 'ACEPTADO',
            self::PENDIENTE => 'PENDIENTE',
            self::RECHAZADO => 'RECHAZADO',
        };
    }


    // Función para obtener todos los valores del enum
    public static function getEnumValues(): array {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}

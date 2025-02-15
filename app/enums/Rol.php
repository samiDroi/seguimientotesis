<?php

namespace App\Enums;

enum Rol: string
{
    case DIRECTOR = 'DIRECTOR';
    case CODIRECTOR = 'CODIRECTOR';
    case ASESOR = 'ASESOR';
    case TUTOR = 'TUTOR';

    public function label(): string
    {
        return match ($this) {
            self::DIRECTOR => 'Director',
            self::CODIRECTOR => 'Codirector',
            self::ASESOR => 'Asesor',
            self::TUTOR => 'Tutor',
        };
    }


    // Función para obtener todos los valores del enum
    public static function getEnumValues(): array {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}



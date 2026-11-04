<?php

declare(strict_types=1);

namespace Ejemplos\E21;

function mimePermitido(string $mime): bool
{
    return in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true);
}

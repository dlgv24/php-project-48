<?php

namespace Differ\Formatters;

function format(\stdClass $data, string $format): string
{
    switch ($format) {
        case 'stylish':
            return Stylish\format($data);
        case 'plain':
            return Plain\format($data);
        case 'json':
            return Json\format($data);
        default:
            throw new \Exception("Unsupported format: {$format}!");
    }
}

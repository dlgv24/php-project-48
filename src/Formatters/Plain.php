<?php

namespace Differ\Formatters\Plain;

use function Symfony\Component\String\s;

function formatValue(mixed $data): string
{
    if (is_object($data) || is_array($data)) {
        $result = '[complex value]';
    } elseif (is_bool($data)) {
        $result = $data ? 'true' : 'false';
    } elseif (is_null($data)) {
        $result = 'null';
    } elseif (is_string($data)) {
        $result = "'{$data}'";
    } else {
        $result = (string) $data;
    }
    return $result;
}

function formatObject(mixed $data, string $prefix): array
{
    if (!is_object($data)) {
        return [];
    }

    $result = [];
    foreach ($data as $k => $v) {
        $ks = s($k);
        $mns = $ks->startsWith('- ');
        $pls = $ks->startsWith('+ ');

        $realKey = $mns || $pls ? (string) $ks->slice(2) : $k;
        $fullKey = empty($prefix) ? $realKey : implode('.', [$prefix, $realKey]);

        if ($mns) {
            if (property_exists($data, '+ ' . $realKey)) {
                $oldValue = formatValue($v);
                $newValue = formatValue($data->{'+ ' . $realKey});
                $result[] = "Property '{$fullKey}' was updated. From {$oldValue} to {$newValue}";
                unset($data->{'+ ' . $realKey});
            } else {
                $result[] = "Property '{$fullKey}' was removed";
            }
        } elseif ($pls) {
            $newValue = formatValue($v);
            $result[] = "Property '{$fullKey}' was added with value: {$newValue}";
        } else {
            $result = array_merge($result, formatObject($v, $fullKey));
        }
    }
    return $result;
}

function format(\stdClass $data): string
{
    $lines = formatObject($data, '');
    return s("\n")->join($lines);
}

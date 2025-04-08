<?php

namespace Differ\Formatters\Plain;

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

    $vars = get_object_vars($data);
    $keys = array_keys($vars);

    $result = array_reduce(
        $keys,
        function ($carry, $key) use ($vars, $prefix) {
            $mns = str_starts_with($key, '- ');
            $pls = str_starts_with($key, '+ ');

            $realKey = $mns || $pls ? mb_substr($key, 2) : $key;
            $fullKey = $prefix === '' ? $realKey : implode('.', [$prefix, $realKey]);

            if ($mns) {
                $newKey = '+ ' . $realKey;
                if (array_key_exists($newKey, $vars)) {
                    $oldValue = formatValue($vars[$key]);
                    $newValue = formatValue($vars[$newKey]);
                    $res = array_merge($carry, ["Property '{$fullKey}' was updated. From {$oldValue} to {$newValue}"]);
                } else {
                    $res = array_merge($carry, ["Property '{$fullKey}' was removed"]);
                }
            } elseif ($pls) {
                $newKey = '- ' . $realKey;
                if (array_key_exists($newKey, $vars)) {
                    $res = $carry;
                } else {
                    $value = formatValue($vars[$key]);
                    $res = array_merge($carry, ["Property '{$fullKey}' was added with value: {$value}"]);
                }
            } else {
                $res = array_merge($carry, formatObject($vars[$key], $fullKey));
            }
            return $res;
        },
        []
    );

    return $result;
}

function format(\stdClass $data): string
{
    $lines = formatObject($data, '');
    return implode("\n", $lines);
}

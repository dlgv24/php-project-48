<?php

namespace Differ\Formatters\Stylish;

const INDENT_IN_SPACES = 4;

function format(mixed $data, int $indent = 0): string
{
    if (is_object($data)) {
        $vars = get_object_vars($data);
        $keys = array_keys($vars);

        $lines = array_reduce(
            $keys,
            function ($carry, $key) use ($indent, $vars) {
                $value = $vars[$key];
                $fmt = format($value, $indent + INDENT_IN_SPACES);
                if (str_starts_with($key, '+ ') || str_starts_with($key, '- ')) {
                    $prefix = str_repeat(' ', $indent + INDENT_IN_SPACES - 2);
                } else {
                    $prefix = str_repeat(' ', $indent + INDENT_IN_SPACES);
                }

                $carry[] = "{$prefix}{$key}: {$fmt}";
                return $carry;
            },
            ['{']
        );

        $lines[] = str_repeat(' ', $indent) . "}";
        $result = implode("\n", $lines);
    } elseif (is_array($data)) {
        $prefix = str_repeat(' ', $indent + INDENT_IN_SPACES);

        $lines = array_reduce(
            $data,
            function ($carry, $value) use ($indent, $prefix) {
                $fmt = format($value, $indent + INDENT_IN_SPACES);
                $carry[] = "{$prefix}{$fmt}";
                return $carry;
            },
            ['[']
        );

        $lines[] = str_repeat(' ', $indent) . "]";
        $result = implode("\n", $lines);
    } elseif (is_bool($data)) {
        $result = $data ? 'true' : 'false';
    } elseif (is_null($data)) {
        $result = 'null';
    } else {
        $result = (string) $data;
    }
    return $result;
}

<?php

namespace Differ\Formatters\Stylish;

const INDENT_IN_SPACES = 4;

function format(mixed $data, int $indent = 0): string
{
    if (is_object($data)) {
        $lines = ["{"];
        $vars = get_object_vars($data);
        $keys = array_keys($vars);

        for ($i = 0, $len = count($keys); $i < $len; $i++) {
            $key = $keys[$i];
            $value = $vars[$key];
            $fmt = format($value, $indent + INDENT_IN_SPACES);
            if (str_starts_with($key, '+ ') || str_starts_with($key, '- ')) {
                $prefix = str_repeat(' ', $indent + INDENT_IN_SPACES - 2);
            } else {
                $prefix = str_repeat(' ', $indent + INDENT_IN_SPACES);
            }

            $lines[] = "{$prefix}{$key}: {$fmt}";
        }

        $lines[] = str_repeat(' ', $indent) . "}";
        $result = implode("\n", $lines);
    } elseif (is_array($data)) {
        $lines = ["["];
        $prefix = str_repeat(' ', $indent + INDENT_IN_SPACES);

        for ($i = 0, $len = count($data); $i < $len; $i++) {
            $fmt = format($data[$i], $indent + INDENT_IN_SPACES);
            $lines[] = "{$prefix}{$fmt}";
        }

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

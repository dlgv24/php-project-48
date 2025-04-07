<?php

namespace Differ\Formatters\Stylish;

use function Symfony\Component\String\s;

const INDENT_IN_SPACES = 4;

function format(mixed $data, int $indent = 0): string
{
    if (is_object($data)) {
        $result = s("{\n");
        $prefix = s(' ')->repeat($indent + INDENT_IN_SPACES);
        foreach ($data as $k => $v) {
            $fmt = format($v, $indent + INDENT_IN_SPACES);
            $result = $result->append("{$prefix}{$k}: {$fmt}\n");
        }
        $result = $result->append(s(' ')->repeat($indent))->append('}');
    } elseif (is_array($data)) {
        $result = s("[\n");
        $prefix = s(' ')->repeat($indent + INDENT_IN_SPACES);
        foreach ($data as $v) {
            $fmt = format($v, $indent + INDENT_IN_SPACES);
            $result = $result->append("{$prefix}{$fmt}\n");
        }
        $result = $result->append(s(' ')->repeat($indent))->append(']');
    } elseif (is_bool($data)) {
        $result = $data ? 'true' : 'false';
    } elseif (is_null($data)) {
        $result = 'null';
    } else {
        $result = (string) $data;
    }
    return $result;
}

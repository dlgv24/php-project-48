<?php

namespace Differ\Differ;

use Differ\Parsers;
use Differ\Formatters;
use Illuminate\Support\Collection;

function sortKeys(mixed $data): mixed
{
    if (is_object($data)) {
        $vars = collect(get_object_vars($data))->sortKeysUsing(
            function ($a, $b) {
                $a = preg_replace('@^[+-] @', '', $a) ?? '';
                $b = preg_replace('@^[+-] @', '', $b) ?? '';
                return strcasecmp($a, $b);
            }
        );
    } elseif (is_array($data)) {
        $vars = collect($data);
    } else {
        return $data;
    }

    $sorted = $vars->map(fn($var) => sortKeys($var))->all();

    if (is_object($data)) {
        return (object) $sorted;
    }

    return $sorted;
}

function diff(mixed $data1, mixed $data2): \stdClass|null
{
    if (!is_object($data1) || !is_object($data2)) {
        return null;
    }

    $vars1 = get_object_vars($data1);
    $vars2 = get_object_vars($data2);

    $keys1 = array_keys($vars1);
    $keys2 = array_keys($vars2);

    $result1 = array_reduce(
        $keys1,
        function ($carry, $key) use ($vars1, $vars2) {
            if (array_key_exists($key, $vars2)) {
                $diff = diff($vars1[$key], $vars2[$key]);
                if (is_null($diff)) {
                    if ($vars1[$key] === $vars2[$key]) {
                        $res = [$key => $vars1[$key]];
                    } else {
                        $res = [
                            "- {$key}" => $vars1[$key],
                            "+ {$key}" => $vars2[$key]
                        ];
                    }
                } else {
                    $res = [$key => $diff];
                }
            } else {
                 $res = ["- {$key}" => $vars1[$key]];
            }
            return array_merge($carry, $res);
        },
        []
    );

    $result2 = array_reduce(
        $keys2,
        function ($carry, $key) use ($vars1, $vars2) {
            if (!array_key_exists($key, $vars1)) {
                $res = ["+ {$key}" => $vars2[$key]];
            } else {
                $res = [];
            }
            return array_merge($carry, $res);
        },
        []
    );

    return (object) array_merge($result1, $result2);
}

function genDiff(string $filename1, string $filename2, string $type = 'stylish'): mixed
{
    try {
        $data1 = Parsers\parse($filename1);
        $data2 = Parsers\parse($filename2);
        $result = diff($data1, $data2);
        $sortedResult = sortKeys($result);
        $resultStr = Formatters\format($sortedResult, $type);
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    return $resultStr;
}

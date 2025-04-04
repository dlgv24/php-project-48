<?php

namespace Differ\Differ;

use Differ\Parsers;

function sortKeys(mixed &$data): void
{
    if (is_object($data)) {
        $tmp = (array) $data;
        uksort(
            $tmp,
            function ($a, $b) {
                $a = preg_replace('@^[+-] @', '', $a);
                $b = preg_replace('@^[+-] @', '', $b);
                return strcasecmp($a, $b);
            }
        );
        $data = (object) $tmp;
        foreach ($data as $key => $_) {
            sortKeys($data->$key);
        }
    } elseif (is_array($data)) {
        foreach ($data as $key => $_) {
            sortKeys($data[$key]);
        }
    }
}

function diff(mixed $data1, mixed $data2): \stdClass|array|null
{
    if (is_object($data1) && is_object($data2)) {
        $result = new \stdClass();
        foreach ($data1 as $key => $value) {
            if (property_exists($data2, $key)) {
                $diff = diff($value, $data2->$key);
                if (is_null($diff)) {
                    if ($value === $data2->$key) {
                        $result->$key = $value;
                    } else {
                        $result->{"- $key"} = $value;
                        $result->{"+ $key"} = $data2->$key;
                    }
                } else {
                    $result->$key = $diff;
                }
            } else {
                $result->{"- $key"} = $value;
            }
            unset($data2->$key);
        }
        foreach ($data2 as $key => $value) {
            $result->{"+ $key"} = $value;
        }
        return $result;
    } elseif (is_array($data1) && is_array($data2)) {
        return null;
    }
    return null;
}

function genDiff(string $filename1, string $filename2): mixed
{
    try {
        $data1 = Parsers\parseJson($filename1);
        $data2 = Parsers\parseJson($filename2);
    } catch (\Exception $e) {
        return $e->getMessage();
    }

    $result = diff($data1, $data2);
    sortKeys($result);
    return json_encode($result, JSON_PRETTY_PRINT);
}

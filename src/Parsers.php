<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getFileContent(string $filename): string
{
    $content = @file_get_contents($filename);
    if ($content === false) {
        throw new \Exception("Unable to get content: $filename");
    }
    return $content;
}

function parseJson(string $filename): mixed
{
    $content = getFileContent($filename);
    $json = json_decode($content);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception(json_last_error_msg() . ': ' . $filename);
    }
    return $json;
}

function parseYaml(string $filename): mixed
{
    $content = getFileContent($filename);
    $yaml = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    return $yaml;
}

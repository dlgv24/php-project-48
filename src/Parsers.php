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

function parse(string $filename): mixed
{
    $content = getFileContent($filename);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    switch ($extension) {
        case 'json':
            $result = json_decode($content);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception(json_last_error_msg() . ': ' . $filename);
            }
            break;
        case 'yml':
        case 'yaml':
            $result = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new \Exception("Unsupported extension: {$extenstion}!");
    }
    return $result;
}

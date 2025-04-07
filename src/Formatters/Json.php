<?php

namespace Differ\Formatters\Json;

function format(\stdClass $data): string
{
    return json_encode($data, JSON_PRETTY_PRINT);
}

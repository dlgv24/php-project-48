<?php

namespace Code\Cli;

use Docopt;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOC;

function run(): void
{
    $args = Docopt::handle(DOC, ['version' => 'gendiff 1.0']);
}

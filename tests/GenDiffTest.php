<?php

namespace Differ\tests\GenDiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

const FIXTURES_DIR = 'tests/fixtures/';

class GenDiffTest extends TestCase {
    public function testJson12()
    {
        $expected = file_get_contents(FIXTURES_DIR . 'expected1-2.json');
        $result = genDiff(FIXTURES_DIR . 'file1.json', FIXTURES_DIR . 'file2.json');
        $this->assertEquals($expected, $result);
    }

    public function testJson34()
    {
        $expected = file_get_contents(FIXTURES_DIR . 'expected3-4.json');
        $result = genDiff(FIXTURES_DIR . 'file3.json', FIXTURES_DIR . 'file4.json');
        $this->assertEquals($expected, $result);
    }
}

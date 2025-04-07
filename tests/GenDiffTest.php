<?php

namespace Differ\tests\GenDiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

const FIXTURES_DIR = 'tests/fixtures/';

class GenDiffTest extends TestCase {
    public function testJson()
    {
        $expected = file_get_contents(FIXTURES_DIR . 'expected1-2.json');
        $result = genDiff(FIXTURES_DIR . 'file1.json', FIXTURES_DIR . 'file2.json', 'json');
        $this->assertEquals($expected, $result);

        $expected = file_get_contents(FIXTURES_DIR . 'expected3-4.json');
        $result = genDiff(FIXTURES_DIR . 'file3.json', FIXTURES_DIR . 'file4.json', 'json');
        $this->assertEquals($expected, $result);
    }

    public function testStylish()
    {
        $expected = file_get_contents(FIXTURES_DIR . 'expected1-2.stylish');
        $result = genDiff(FIXTURES_DIR . 'file1.json', FIXTURES_DIR . 'file2.json', 'stylish');
        $this->assertEquals($expected, $result);

        $expected = file_get_contents(FIXTURES_DIR . 'expected3-4.stylish');
        $result = genDiff(FIXTURES_DIR . 'file3.json', FIXTURES_DIR . 'file4.json', 'stylish');
        $this->assertEquals($expected, $result);
    }

    public function testPlain()
    {
        $expected = file_get_contents(FIXTURES_DIR . 'expected1-2.plain');
        $result = genDiff(FIXTURES_DIR . 'file1.json', FIXTURES_DIR . 'file2.json', 'plain');
        $this->assertEquals($expected, $result);

        $expected = file_get_contents(FIXTURES_DIR . 'expected3-4.plain');
        $result = genDiff(FIXTURES_DIR . 'file3.json', FIXTURES_DIR . 'file4.json', 'plain');
        $this->assertEquals($expected, $result);
    }

    public function testYaml()
    {
        $expected = file_get_contents(FIXTURES_DIR . 'expected3-4.json');
        $result = genDiff(FIXTURES_DIR . 'file3.yaml', FIXTURES_DIR . 'file4.yaml', 'json');
        $this->assertEquals($expected, $result);
    }
}

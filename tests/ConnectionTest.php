<?php
namespace SykesCottages\BranchPruneTest;

use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\Connections\Curl;

class ConnectionTest extends TestCase
{
    public function testMakeURL()
    {
        $curlInstance = new Curl('user', 'pass');
        $curl = new \ReflectionClass(Curl::class);
        $method = $curl->getMethod('makeURL');
        $method->setAccessible(true);

        $parsed = $method->invoke($curlInstance, 'https://user:pass@example.org/test/?one=1', ['two' => 2]);

        $expected = 'https://user:pass@example.org/test/?one=1&two=2';
        $this->assertSame($expected, $parsed);
    }
}

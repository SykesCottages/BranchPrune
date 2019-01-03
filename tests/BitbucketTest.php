<?php
namespace SykesCottages\BranchPruneTest;

use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\CodeManagers\Bitbucket;
use SykesCottages\BranchPrune\Connection;
use SykesCottages\BranchPrune\Options;

class BitbucketTest extends TestCase
{
    protected $bitbucket;
    protected $connection;
    protected $options;

    public function setUp()
    {
        parent::setUp();
        $this->connection = $this->createMock(Connection::class);
        $this->options = $this->createMock(Options::class);

        $this->bitbucket = new Bitbucket($this->connection, $this->options);
    }

    public function testAllBranches()
    {
        $this->connection->method('get')
            ->willReturn((object) [
                'values' => [
                    (object) [
                        'displayId' => 'test',
                        'latestCommit' => 'abcd'
                    ]
                ]
            ]);
        $branches = $this->bitbucket->getAllBranches();

        $this->assertEquals(1, count($branches));
        $this->assertEquals('test', $branches[0]->name);
    }

    public function testDeleteBranch()
    {
        $this->connection->method('delete')
            ->willReturn(null);

        $this->assertTrue($this->bitbucket->deleteBranch('test'));
    }

    public function testCodeOnMaster()
    {
        $this->connection->method('get')
            ->willReturn((object) [
                'values' => [
                    (object) ['displayId' => 'master']
                ]
            ]);

        $this->assertTrue($this->bitbucket->checkForCodeOnMaster('abcd'));
    }

    public function testCodeNotOnMaster()
    {
        $this->connection->method('get')
            ->willReturn((object) [
                'values' => [
                    (object) ['displayId' => 'abcd']
                ]
            ]);

        $this->assertFalse($this->bitbucket->checkForCodeOnMaster('abcd'));
    }
}

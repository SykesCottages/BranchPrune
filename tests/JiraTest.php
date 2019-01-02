<?php
namespace SykesCottages\BranchPruneTest;

use Exception;
use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\Connection;
use SykesCottages\BranchPrune\Jira;
use SykesCottages\BranchPrune\Options;

class JiraTest extends TestCase
{
    public function testMissingOption()
    {
        $this->expectException(Exception::class);
        $connection = $this->createMock(Connection::class);

        $option = $this->createMock(Options::class);

        new Jira($connection, $option);
    }

    public function testMissingIssues()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No Open issues, there needs to be at least one open");

        putenv('JIRA_URL=test');
        $connection = $this->createMock(Connection::class);
        $connection->method('post')->willReturn('');

        $option = $this->createMock(Options::class);

        $jira = new Jira($connection, $option);

        $jira->getOpenJiraIssues();
    }

    public function testFullRun()
    {
        putenv('JIRA_URL=test');
        $connection = $this->createMock(Connection::class);
        $expected = (object)
            [
                'issues' => [
                    (object) ['key' => 1],
                    (object) ['key' => 2]
                ]
            ];

        $connection->method('post')->willReturn($expected);

        $option = $this->createMock(Options::class);

        $jira = new Jira($connection, $option);

        $this->assertSame([1,2], $jira->getOpenJiraIssues());
    }
}

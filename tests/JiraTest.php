<?php

namespace SykesCottages\BranchPruneTest;

use Exception;
use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\Connection;
use SykesCottages\BranchPrune\IssueTracker\Jira;
use SykesCottages\BranchPrune\Options;

class JiraTest extends TestCase
{
    public function testMissingIssues(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No Open issues, there needs to be at least one open");

        putenv('JIRA_URL=test');
        $connection = $this->createMock(Connection::class);
        $connection->method('post')->willReturn('');

        $option = $this->createMock(Options::class);

        $jira = new Jira($connection, $option);

        $jira->getOpenIssues();
    }

    public function testFullRun(): void
    {
        putenv('JIRA_URL=test');
        $connection = $this->createMock(Connection::class);
        $expected = (object)
        [
            'issues' => [
                (object)['key' => 1],
                (object)['key' => 2]
            ]
        ];

        $connection->method('post')->willReturn($expected);

        $option = $this->createMock(Options::class);

        $jira = new Jira($connection, $option);

        $this->assertSame([1, 2], $jira->getOpenIssues());
    }
}

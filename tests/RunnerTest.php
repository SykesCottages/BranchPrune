<?php
namespace SykesCottages\BranchPruneTest;

use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\BranchInfo;
use SykesCottages\BranchPrune\CodeManager;
use SykesCottages\BranchPrune\Jira;
use SykesCottages\BranchPrune\Options;
use SykesCottages\BranchPrune\Runner;

class RunnerTest extends TestCase
{
    public function testCleanBranches()
    {
        $jira = $this->createMock(Jira::class);
        $manager = $this->createMock(CodeManager::class);
        $options = $this->createMock(Options::class);

        $jira->method('getOpenJiraIssues')
           ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $manager->method('getAllBranches')
           ->willReturn([$branchInfo]);

        $manager->method('deleteBranch')
           ->with('test');

        $runner = new Runner($jira, $manager, $options);

        $this->assertNull($runner->cleanBranches());
    }
}

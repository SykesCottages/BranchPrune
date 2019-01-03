<?php
namespace SykesCottages\BranchPruneTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\BranchInfo;
use SykesCottages\BranchPrune\CodeManager;
use SykesCottages\BranchPrune\Jira;
use SykesCottages\BranchPrune\Options;
use SykesCottages\BranchPrune\Runner;

class RunnerTest extends TestCase
{
    /** @var MockObject */
    protected $jira;
    /** @var MockObject */
    protected $manager;
    /** @var MockObject */
    protected $options;
    /** @var Runner */
    protected $runner;

    public function setUp()
    {
        parent::setUp();

        $this->jira = $this->createMock(Jira::class);
        $this->manager = $this->createMock(CodeManager::class);
        $this->options = $this->createMock(Options::class);

        $this->runner = new Runner($this->jira, $this->manager, $this->options);
    }

    public function testCleanBranches()
    {

        $this->jira->method('getOpenJiraIssues')
           ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $this->manager->method('getAllBranches')
           ->willReturn([$branchInfo]);

        $this->manager->method('deleteBranch')
           ->with('test');


        $this->assertNull($this->runner->cleanBranches());
    }

    public function testNoIgnoreBranch()
    {
        $this->options->method('environment')->willThrowException(new \Exception());

        $this->jira->method('getOpenJiraIssues')
            ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $this->manager->method('getAllBranches')
            ->willReturn([$branchInfo]);

        $this->manager->method('deleteBranch')
            ->with('test');


        $this->assertNull($this->runner->cleanBranches());
    }

    public function testUnmergedCode()
    {
        $this->options->method('get')
            ->with('remove-unmerged-check')
            ->willThrowException(new \Exception());

        $this->jira->method('getOpenJiraIssues')
            ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $this->manager->method('getAllBranches')
            ->willReturn([$branchInfo]);

        $this->manager->method('deleteBranch')
            ->with('test');


        $this->assertNull($this->runner->cleanBranches());
    }
}

<?php

namespace SykesCottages\BranchPruneTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SykesCottages\BranchPrune\BranchInfo;
use SykesCottages\BranchPrune\CodeManagers\CodeManagerInterface;
use SykesCottages\BranchPrune\IssueTracker\IssueProviderInterface;
use SykesCottages\BranchPrune\Options;
use SykesCottages\BranchPrune\Runner;

class RunnerTest extends TestCase
{
    /** @var MockObject */
    protected $issueProvider;
    /** @var MockObject */
    protected $manager;
    /** @var MockObject */
    protected $options;
    /** @var Runner */
    protected $runner;

    public function setUp(): void
    {
        parent::setUp();

        $this->issueProvider = $this->createMock(IssueProviderInterface::class);
        $this->manager = $this->createMock(CodeManagerInterface::class);
        $this->options = $this->createMock(Options::class);

        $this->runner = new Runner($this->issueProvider, $this->manager, $this->options);
    }

    public function testCleanBranches(): void
    {
        $this->issueProvider
            ->method('getOpenIssues')
            ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $this->manager
            ->method('getAllBranches')
            ->willReturn([$branchInfo]);

        $this->manager
            ->method('deleteBranch')
            ->with('test');


        $this->assertNull($this->runner->cleanBranches());
    }

    public function testNoIgnoreBranch(): void
    {
        $this->options->method('environment')->willThrowException(new \Exception());

        $this->issueProvider->method('getOpenIssues')
            ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $this->manager
            ->method('getAllBranches')
            ->willReturn([$branchInfo]);

        $this->manager
            ->method('deleteBranch')
            ->with('test');


        $this->assertNull($this->runner->cleanBranches());
    }

    public function testUnmergedCode(): void
    {
        $this->options
            ->method('get')
            ->with('remove-unmerged-check')
            ->willThrowException(new \Exception());

        $this->issueProvider
            ->method('getOpenIssues')
            ->willReturn(['test']);

        $branchInfo = new BranchInfo();
        $branchInfo->name = 'test';
        $branchInfo->commitRef = 'abc';

        $this->manager
            ->method('getAllBranches')
            ->willReturn([$branchInfo]);

        $this->manager
            ->method('deleteBranch')
            ->with('test');

        $this->assertNull($this->runner->cleanBranches());
    }
}

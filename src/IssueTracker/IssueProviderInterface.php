<?php

namespace SykesCottages\BranchPrune\IssueTracker;

interface IssueProviderInterface
{
    public function getOpenIssues(): array;
}
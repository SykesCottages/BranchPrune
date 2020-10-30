<?php
namespace SykesCottages\BranchPrune;

interface CodeManager
{
    /** @return BranchInfo[] */
    public function getAllBranches(): array;

    public function deleteBranch(string $branchName): bool;

    public function checkForCodeOnMaster(string $commit): bool;
}

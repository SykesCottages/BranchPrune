<?php
namespace SykesCottages\BranchPrune;

interface CodeManager
{
    /** @return BranchInfo[] */
    public function getAllBranches(): array;

    public function deleteBranch(string $branchName);

    public function checkForCodeOnMaster(string $commit);
}

<?php

namespace SykesCottages\BranchPrune\CodeManagers;

use SykesCottages\BranchPrune\BranchInfo;

interface CodeManagerInterface
{
    /** @return BranchInfo[] */
    public function getAllBranches(): array;

    public function deleteBranch(string $branchName): bool;

    public function checkForCodeOnMaster(string $commit): bool;
}

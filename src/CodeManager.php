<?php
namespace SykesCottages\BranchPrune;

interface CodeManager
{
    public function getAllBranches();

    public function deleteBranch(string $branchName);

    public function checkForCodeOnMaster(string $commit);
}

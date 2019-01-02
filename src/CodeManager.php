<?php
namespace SykesCottages\BranchPrune;

interface CodeManager
{
    public function getAllBranches();

    public function deleteBranch($branchName);

    public function checkForCodeOnMaster($commit);
}

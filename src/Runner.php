<?php

namespace SykesCottages\BranchPrune;

use Exception;
use SykesCottages\BranchPrune\IssueTracker\IssueProviderInterface;

class Runner
{

    /**
     * @var IssueProviderInterface
     */
    private $issueProvider;
    /**
     * @var CodeManager
     */
    private $manager;
    /**
     * @var Options
     */
    private $options;

    public function __construct(IssueProviderInterface $issueProvider, CodeManager $manager, Options $options)
    {
        $this->issueProvider = $issueProvider;
        $this->manager = $manager;
        $this->options = $options;
    }

    public function cleanBranches(): void
    {
        $issues = $this->issueProvider->getOpenIssues();
        $issues = array_map(function ($a) {
            return "$a-";
        }, $issues);

        try {
            $protectedBranches = $this->options->environment('IGNORE_BRANCH');
            $protectedBranches = explode(',', $protectedBranches);
        } catch (Exception $exception) {
            $protectedBranches = [];
        }

        $searchStrings = array_merge($issues, (array)$protectedBranches);

        try {
            $checkCodeOnMaster = !$this->options->get('remove-unmerged-check');
        } catch (Exception $exception) {
            $checkCodeOnMaster = true;
        }

        foreach ($this->manager->getAllBranches() as $branch) {
            if ($branch->name != "master" &&
                str_replace($searchStrings, '', $branch->name) == $branch->name &&
                (!$checkCodeOnMaster || $this->manager->checkForCodeOnMaster($branch->commitRef))
            ) {
                echo "Deleting $branch->name\n";

                try {
                    $this->options->get('dry-run');
                } catch (Exception $exception) {
                    $this->manager->deleteBranch($branch->name);
                }
            }
        }
    }
}

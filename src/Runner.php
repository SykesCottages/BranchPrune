<?php

namespace SykesCottages\BranchPrune;

use Exception;

class Runner
{

    /**
     * @var Jira
     */
    private $jira;
    /**
     * @var CodeManager
     */
    private $manager;
    /**
     * @var Options
     */
    private $options;

    public function __construct(Jira $jira, CodeManager $manager, Options $options)
    {
        $this->jira = $jira;
        $this->manager = $manager;
        $this->options = $options;
    }

    public function cleanBranches()
    {
        $issues = $this->jira->getOpenJiraIssues();
        $issues = array_map(function ($a) {
            return "$a-";
        }, $issues);

        try {
            $protectedBranches = $this->options->environment('IGNORE_BRANCH');
            $protectedBranches = explode(',', $protectedBranches);
        } catch (Exception $exception) {
            $protectedBranches = [];
        }

        $searchStrings = array_merge($issues, (array) $protectedBranches);

        try {
            $checkCodeOnMaster = !$this->options->get('remove-unmerged-check');
        } catch (Exception $exception) {
            $checkCodeOnMaster = true;
        }

        foreach ($this->manager->getAllBranches() as $branch) {
            if ($branch->name != "master" &&
                str_replace($searchStrings, '', $branch->name) == $branch->name &&
                (!$checkCodeOnMaster || $this->manager->checkForCodeOnMaster($branch->commitRef) )
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

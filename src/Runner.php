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

    function cleanBranches()
    {
        $issues = $this->jira->getOpenJiraIssues();
        $issues = array_map(function ($a){ return "$a-"; }, $issues);

        $protectedBranches = getenv('IGNORE_BRANCH');
        if ($protectedBranches) {
            $protectedBranches = explode(',', $protectedBranches);
        }

        $searchStrings = array_merge($issues, (array) $protectedBranches);

        try {
            $checkCodeOnMaster = !$this->options->get('remove-unmerged-check');
        } catch (Exception $exception) {
            $checkCodeOnMaster = true;
        }

        foreach($this->manager->getAllBranches()->values as $branch) {
            if (
                $branch->displayId != "master" &&
                str_replace($searchStrings, '', $branch->displayId) == $branch->displayId &&
                (!$checkCodeOnMaster || $this->manager->checkForCodeOnMaster($branch->latestCommit) )
            ) {

                echo "Deleting $branch->displayId\n";

                try {
                    $this->options->get('dry-run');
                } catch (Exception $exception) {
                    $this->manager->deleteBranch($branch->displayId);
                }
            }
        }
    }

}

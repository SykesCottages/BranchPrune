<?php
namespace SykesCottages\BranchPrune\CodeManagers;

use Exception;
use SykesCottages\BranchPrune\BranchInfo;
use SykesCottages\BranchPrune\CodeManager;
use SykesCottages\BranchPrune\Connection;
use SykesCottages\BranchPrune\Options;

class Bitbucket implements CodeManager
{

    protected $url;
    protected $branchUrl;
    protected $key;
    protected $name;

    private $connection;

    public function __construct(Connection $connection, Options $options)
    {
        $this->connection = $connection;

        $url = $options->environment('BITBUCKET_URL');
        $url = rtrim($url, '/');

        $this->url = $url . '/api/1.0/projects/';
        $this->branchUrl = $url . '/branch-utils/1.0/projects/';

        // need also the long and short codes
        $this->key = $options->get('project-key');
        $this->name = $options->get('project-name');
    }

    /**
     * @return BranchInfo[]
     */
    public function getAllBranches(): array
    {
        $branches = $this->connection->get(
            $this->url . "{$this->key}/repos/{$this->name}/branches",
            [
                'details' => true,
                'limit' => 1000,
            ]
        );
        $formattedBranches = [];

        foreach ($branches->values as $branch) {
            $branchInfo = new BranchInfo();
            $branchInfo->name = $branch->displayId;
            $branchInfo->commitRef = $branch->latestCommit;

            $formattedBranches[] = $branchInfo;
        }

        return $formattedBranches;
    }

    public function deleteBranch(string $branchName): bool
    {
        $data = [
            'name' => "refs/heads/" . $branchName,
            'dryRun' => false
        ];
        $url =  $this->branchUrl . "{$this->key}/repos/{$this->name}/branches";

        //null is good and means it worked!
        return $this->connection->delete($url, $data) == null;
    }

    public function checkForCodeOnMaster(string $commit): bool
    {
        $result = $this->connection->get(
            $this->branchUrl . "{$this->key}/repos/{$this->name}/branches/info/{$commit}"
        );

        foreach ($result->values as $branches) {
            if ($branches->displayId == "master") {
                return true;
            }
        }

        return false;
    }
}

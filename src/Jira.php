<?php
namespace SykesCottages\BranchPrune;

use Exception;

class Jira
{

    protected $connection;
    protected $url;

    protected $projectKey;

    public function __construct(Connection $connection, Options $options)
    {
        $this->connection = $connection;
        $this->url = $options->environment('JIRA_URL');

        $this->projectKey = $options->get('project-key');
    }

    public function getOpenJiraIssues()
    {
        $project = $this->projectKey;

        $data = [
            "jql"           => "project = $project and statusCategory != Done",
            "startAt"       => 0,
            "maxResults"    => 1000,
            "fields"        => [
                "summary",
                "status",
                "assignee"
            ],
        ];

        $result = $this->connection->post(
            $this->url . 'search',
            $data
        );

        if (!$result || !$result->issues) {
            throw new Exception("No Open issues, there needs to be at least one open");
        }
        $issues = [];
        foreach ($result->issues as $issue) {
            $issues[] = $issue->key;
        }

        return $issues;
    }
}

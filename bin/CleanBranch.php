<?php
use SykesCottages\BranchPrune\CodeManagers\Bitbucket;
use SykesCottages\BranchPrune\Connections\Curl;
use SykesCottages\BranchPrune\Jira;
use SykesCottages\BranchPrune\Options;
use SykesCottages\BranchPrune\Runner;

require_once(__DIR__ . '/../vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->overload();

$connection = new Curl(getenv('USERNAME'), getenv('PASSWORD'));
$options = new Options();

$jira = new Jira($connection, $options);
$codeManager = new Bitbucket($connection, $options);

$runner = new Runner($jira, $codeManager, $options);

$runner ->cleanBranches();

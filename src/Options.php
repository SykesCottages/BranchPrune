<?php
namespace SykesCottages\BranchPrune;

use Exception;

class Options
{
    private $cache;

    public function get(string $key): string
    {
        if (!$this->cache) {
            $this->parseOptions();
        }

        if (!isset($this->cache[$key])) {
            throw new Exception("Missing config item '$key'");
        }

        if ($this->cache[$key] === false) {
            return true;
        }

        return $this->cache[$key];
    }

    public function environment(string $key): string
    {
        $environment = getenv($key);

        if (!$environment) {
            throw new Exception("Missing environment variable $key");
        }

        return (string) $environment;
    }

    private function parseOptions(): void
    {
        $this->cache = getopt(
            '',
            [
                'project-key:',
                'project-name:',
                'dry-run',
                'remove-unmerged-check',
            ]
        );
    }
}

<?php
namespace SykesCottages\BranchPrune;

use Exception;

class Options
{
    private $cache;
    public function get(string $key)
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

    private function parseOptions()
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

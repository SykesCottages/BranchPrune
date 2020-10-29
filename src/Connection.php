<?php
namespace SykesCottages\BranchPrune;

abstract class Connection
{
    protected $username;
    protected $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    abstract public function get(string $url, array $getOptions = []);

    abstract public function post(string $url, array $postOptions, array $getOptions = []);

    abstract public function delete(string $url, array $postOptions, array $getOptions = []);

    protected function makeURL(string $url, array $getOptions): string
    {
        $parsed_url = parse_url($url);
        $query = [];
        $parsed_url['query'] = isset($parsed_url['query']) ? parse_str($parsed_url['query'], $query) : [];

        if ($query) {
            $parsed_url['query'] = $query;
        }

        $parsed_url['query'] = array_merge($parsed_url['query'], $getOptions);
        $parsed_url['query'] = http_build_query($parsed_url['query']);

        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = $parsed_url['host'] ?? '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = $parsed_url['user'] ?? '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = $parsed_url['path'] ?? '';
        $query    = $parsed_url['query'] ? '?' . $parsed_url['query'] : '';

        return "$scheme$user$pass$host$port$path$query";
    }
}

<?php

namespace SykesCottages\BranchPrune\Connections;

use SykesCottages\BranchPrune\Connection;

class Curl extends Connection
{
    protected $handle;

    public function __construct($username, $password)
    {
        parent::__construct($username, $password);

        $this->handle = curl_init();
    }

    public function get(string $url, array $getOptions = []): mixed
    {
        curl_reset($this->handle);
        curl_setopt_array(
            $this->handle,
            [
                CURLOPT_URL => $this->makeURL($url, $getOptions),
                CURLOPT_USERPWD => sprintf('%s:%s', $this->username, $this->password),
                CURLOPT_CUSTOMREQUEST, "GET",
                CURLOPT_RETURNTRANSFER => true,
            ]
        );

        return json_decode(curl_exec($this->handle));
    }

    public function post(string $url, array $postOptions, array $getOptions = []): mixed
    {
        curl_reset($this->handle);
        curl_setopt_array(
            $this->handle,
            [
                CURLOPT_URL => $this->makeURL($url, $getOptions),
                CURLOPT_USERPWD => sprintf('%s:%s', $this->username, $this->password),
                CURLOPT_POSTFIELDS => json_encode($postOptions),
                CURLOPT_CUSTOMREQUEST, "POST",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-type: application/json'
                ],
            ]
        );

        return json_decode(curl_exec($this->handle));
    }

    public function delete(string $url, array $postOptions, array $getOptions = []): mixed
    {
        curl_reset($this->handle);
        curl_setopt_array(
            $this->handle,
            [
                CURLOPT_URL => $this->makeURL($url, $getOptions),
                CURLOPT_USERPWD => sprintf('%s:%s', $this->username, $this->password),
                CURLOPT_POSTFIELDS => json_encode($postOptions),
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-type: application/json'
                ],
            ]
        );

        return json_decode(curl_exec($this->handle));
    }
}

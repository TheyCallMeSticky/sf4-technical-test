<?php

namespace stadline\appBundle\Service;

use Unirest;

/**
 * Service de recherche sur gihub
 */
class GitHubFinder {

    private $data;

    public function __construct() {

    }

    public function getUsers($username, $page = 1) {

        $headers = array('Accept' => 'application/json');
        $query = array('q' => 'type:user%20' . $username . '%20in:login&page=' . $page);
        $this->data = $this->request('search/users', $headers, $query);
        $userList = [];
        if (property_exists($this->data, 'total_count')) {
            if ($this->data->total_count > 0) {
                $userList = $this->data->items;
            }
        }
        return $userList;
    }

    public function getNbPage() {
        if ($this->data->total_count > 30) {
            return intdiv($this->data->total_count, count($this->data->items)) + 1;
        } else {
            return 1;
        }
    }

    public function getRepos($username, $page = 1) {
        $headers = array('Accept' => 'application/json');
        $query = [];
        $search = 'users/' . $username . '/repos?' . $page;
        $this->data = $this->request($search, $headers, $query);
        return $this->data;
    }

    public function request($search, $headers, $query) {
        return json_decode(Unirest\Request::get('https://api.github.com/' . $search, $headers, $query)->raw_body);
    }

}

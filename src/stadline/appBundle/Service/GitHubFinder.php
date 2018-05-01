<?php

namespace stadline\appBundle\Service;

use Unirest;

/**
 * Service de recherche sur gihub
 */
class GitHubFinder {

    private $data;
    private $header;

    public function __construct() {

    }

    public function getUsers($username, $page = 1) {

        $headers = array('Accept' => 'application/json');
        $query = array('q' => 'type:user%20' . $username . '%20in:login&page=' . $page);

        $this->getData('search/users', $headers, $query);

        $userList = [];
        if (property_exists($this->data, 'total_count')) {
            if ($this->data->total_count > 0) {
                $userList = $this->data->items;
            }
        }
        return $userList;
    }

    public function getNbPage() {

        $pageMax = 1;
        if ($this->header && array_key_exists('Link', $this->header)) {
            $linkstr = $this->header['Link'];

            foreach (explode('", <', $linkstr) as $line) {
                if (strpos($line, 'rel="last') > 1) {
                    $pageMax = intval(explode('>;', explode('page=', $line)[1])[0]);
                }
            }
        }

        return $pageMax;
    }

    public function getRepos($username, $page = 1) {
        $headers = array('Accept' => 'application/json');
        $query = [];
        $search = 'users/' . $username . '/repos?' . $page;
        $this->getData($search, $headers, $query);
        return $this->data;
    }

    public function request($search, $headers, $query) {
        return Unirest\Request::get('https://api.github.com/' . $search, $headers, $query);
    }

    public function getData($search, $headers, $query) {
        $limit = 10;
        $i = 0;
        do {
            $request = $this->request($search, $headers, $query);
            $this->header = $request->headers;
            $this->data = $request->body;
            $i++;
        } while (!array_key_exists('Link', $this->header) && $limit < $i);
    }

}

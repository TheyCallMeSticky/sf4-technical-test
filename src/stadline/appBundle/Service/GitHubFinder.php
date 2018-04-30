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
        $this->data = $this->getData('search/users', $headers, $query);
        $this->header = $this->getHeader('search/users', $headers, $query);
        $userList = [];
        if (property_exists($this->data, 'total_count')) {
            if ($this->data->total_count > 0) {
                $userList = $this->data->items;
            }
        }
        return $userList;
    }

    public function getNbPage() {

        var_dump($this->header);

        $pageMax = 1;
        if ($this->header) {
            $linkstr = $this->header['Link'];
//            $pageMax = intval(explode('>;', explode('page=', $linkstr)[2])[0]);

            var_dump(explode('", <', $linkstr));
            foreach (explode('", <', $linkstr) as $lin)
                $pageMax = intval(explode('>;', explode('page=', $linkstr)[2])[0]);
        }
        return $pageMax;
        //ci-dessous fonctionne pour les utilisateur mais pas pour les repository...
//        if ($this->data->total_count > 30) {
//            return intdiv($this->data->total_count, count($this->data->items)) + 1;
//        } else {
//            return 1;
//        }
    }

    public function getRepos($username, $page = 1) {
        $headers = array('Accept' => 'application/json');
        $query = [];
        $search = 'users/' . $username . '/repos?' . $page;
        $this->data = $this->getData($search, $headers, $query);
        $this->header = $this->getHeader($search, $headers, $query);
        return $this->data;
    }

    public function request($search, $headers, $query) {
        return Unirest\Request::get('https://api.github.com/' . $search, $headers, $query);
    }

    public function getData($search, $headers, $query) {
        return json_decode($this->request($search, $headers, $query)->raw_body);
    }

    public function getHeader($search, $headers, $query) {
        return $this->request($search, $headers, $query)->headers;
    }

}

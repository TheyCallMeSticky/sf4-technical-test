<?php

namespace stadline\appBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use stadline\appBundle\Service\GitHubFinder;

class DefaultController extends Controller {

    public function indexAction() {
        $user = $this->getUser();
        return $this->render('@stadlineapp/Default/index.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/search-user/{searchedUser}/{page}", name="stadlineapp.search_user" )
     */
    public function searchUser($searchedUser, $page = 1) {
        $githubservice = new GitHubFinder();
        $userList = $githubservice->getUsers($searchedUser, $page);

        $maxPage = $githubservice->getNbPage();

        return $this->render('@stadlineapp/searchUser.html.twig', ['userList' => $userList, 'maxPage' => $maxPage, 'searchedUser' => $searchedUser]);
    }

    /**
     * @Route("/showRepos/{userName}/{page}", name="stadlineapp.show_repos" )
     */
    public function showRepos($userName, $page = 1) {
        $githubservice = new GitHubFinder();
        $userList = $githubservice->getRepos($userName, $page);

        $maxPage = $githubservice->getNbPage();

        return $this->render('@stadlineapp/searchUser.html.twig', ['userList' => $userList, 'maxPage' => $maxPage, 'searchedUser' => $userName]);
    }

}

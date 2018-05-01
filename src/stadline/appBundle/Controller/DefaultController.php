<?php

namespace stadline\appBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use stadline\appBundle\Service\GitHubFinder;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        $user = $this->getUser();
        $defaultData = array('message' => 'rechercher un utilisateur');
        $form = $this->createFormBuilder($defaultData)
                ->add('userSearch', TextType::class)
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            return $this->redirectToRoute("stadlineapp.search_user", ['searchedUser' => $data['userSearch']]);
        }

        return $this->render('@stadlineapp/Default/index.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @Route("/search-user/{searchedUser}/{page}", name="stadlineapp.search_user" )
     */
    public function searchUser($searchedUser, $page = 1) {
        $githubservice = new GitHubFinder();
        $userList = $githubservice->getUsers($searchedUser, $page);

        $maxPage = $githubservice->getNbPage();

        return $this->render('@stadlineapp/searchUser.html.twig', [
                    'userList' => $userList,
                    'maxPage' => $maxPage,
                    'searchedUser' => $searchedUser,
                    'current_page' => $page
        ]);
    }

    /**
     * @Route("/showRepos/{userName}/{page}", name="stadlineapp.show_repos" )
     */
    public function showRepos($userName, $page = 1) {
        $githubservice = new GitHubFinder();
        $repoList = $githubservice->getRepos($userName, $page);

        var_dump($repoList);
        die;

        $maxPage = $githubservice->getNbPage();

        return $this->render('@stadlineapp/showRepos.html.twig', [
                    'repoList' => $repoList,
                    'maxPage' => $maxPage,
                    'username' => $userName,
                    'current_page' => $page
        ]);
    }

    /**
     * @Route("/repo/commentaires/{repo}/{page}", name="stadlineapp.comment_repos" )
     */
    public function seeComments() {

    }

}

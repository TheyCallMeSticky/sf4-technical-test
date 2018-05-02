<?php

namespace stadline\appBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use stadline\appBundle\Service\GitHubFinder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use stadline\appBundle\Entity\Comment;

class DefaultController extends Controller {

    /**
     * @Route("/", name="stadlineapp.homepage" )
     */
    public function indexAction(Request $request) {
        $user = $this->getUser();
        $defaultData = array('message' => 'rechercher un utilisateur');
        $form = $this->createFormBuilder($defaultData)
                ->add('userSearch', TextType::class, ['attr' => ['class' => "form-control", 'placeholder' => "User Github à chercher"], 'label' => false])
                ->add('save', SubmitType::class, array('label' => 'find', 'attr' => ['class' => "btn btn-dark"]))
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

        return $this->render('@stadlineapp/Default/searchUser.html.twig', [
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


        $maxPage = $githubservice->getNbPage();

        return $this->render('@stadlineapp/Default/showRepos.html.twig', [
                    'repoList' => $repoList,
                    'maxPage' => $maxPage,
                    'username' => $userName,
                    'current_page' => $page
        ]);
    }

    /**
     * @Route("/repo/commentaires/{repo_id}", name="stadlineapp.comment_repos" )
     */
    public function seeComments(Request $request) {

        $repo_id = $request->attributes->get('repo_id');

        $githubservice = new GitHubFinder();
        $repo = $githubservice->getRepoById($repo_id);

        $commentsList = $this->getDoctrine()->getRepository(Comment::class)->findBy(['repositoryId' => $repo_id]);


        $username = $this->getUser()->getUserName();

        $comment = new Comment();
        $comment->setUsername($username);
        $comment->setRepositoryId($repo_id);

        $form = $this->createFormBuilder($comment)
                ->add('comment', TextType::class, ['label' => 'commentaire : ', 'attr' => ['class' => "form-control", 'placeholder' => "commentaire"]])
                ->add('save', SubmitType::class, ['label' => 'Ajouter un commentaire', 'attr' => ['class' => "btn btn-dark"]])
                ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->render('@stadlineapp/Default/seeComments.html.twig', array(
                    'form' => $form->createView(),
                    'commentsList' => $commentsList,
                    'repo' => $repo,
        ));
    }

}

<?php

namespace stadline\appBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        $user = $this->getUser();
        return $this->render('@stadlineapp/Default/index.html.twig', ['user' => $user]);
    }

}

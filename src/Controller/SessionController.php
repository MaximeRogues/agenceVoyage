<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session")
     */
    public function index(Request $request)
    {

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController'
        ]);
    }

     /**
     * @Route("/session/add", name="addSession")
     */
    public function addUser(Request $request) {

        $session = $request->getSession();
        $session->set('firstname', 'Hugues');
        $session->set('lastname', 'Blaque-coq');

        return $this->render('session/index.html.twig');
    }

     /**
     * @Route("/session/display", name="displaySession")
     */
    public function display(Request $request) {

        $session = $request->getSession();
        $firstname = $session->get('firstname');
        $lastname = $session->get('lastname');

        return $this->render('session/display.html.twig',[
            'firstname' => $firstname,
            'lastname' => $lastname
        ]);
    }

    /**
     * @Route("/session/delete", name="deleteSession")
     */
    public function deleteSession(Request $request) {

        $session = $request->getSession();
        $session->set('firstname', null);
        $session->set('lastname', null);

        return $this->render('session/delete.html.twig');
    }
}

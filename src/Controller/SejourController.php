<?php

namespace App\Controller;

use App\Entity\Sejour;
use App\Form\SejourType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SejourController extends AbstractController
{
    /**
     * @Route("/sejour", name="sejour")
     */
    public function index()
    {
        // envoie une liste de sejours à index.html
        $sejourRepo = $this->getDoctrine()->getRepository(Sejour::class);
        $sejours = $sejourRepo->findAll();

        return $this->render('sejour/index.html.twig', [
            'controller_name' => 'Le nom du controller, c\'est sejour',
            'sejours' => $sejours
        ]);
    }

    /**
     * @Route("/sejour/{sejour}", name="sejourType")
     */
    public function sejourType($sejour)
    {
        return $this->render('sejour/display.html.twig', [
            'sejour' => $sejour
        ]);
    }

      /**
     * @Route("/add/sejour", name="addSejour")
     */
    public function addSejour(Request $request)
    {
        // je déclare une nouvelle Sejour vide
        $form = $this->createForm(SejourType::class, new Sejour());
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère les données du form
            $newSejour = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            // j'insère la nouvelle Sejour en BDD
            $entityManager->persist($newSejour);
            $entityManager->flush();
        } else {
            return $this->render('sejour/add.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }

        return $this->redirect('/sejour');
    }


}

   

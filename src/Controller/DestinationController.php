<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends AbstractController
{
    /**
     * @Route("/destination", name="destination")
     */
    public function index()
    {
        // envoie une liste de destinations à index.html
        $destinationRepo = $this->getDoctrine()->getRepository(Destination::class);
        $destinations = $destinationRepo->findAll();

        return $this->render('destination/index.html.twig', [
            'controller_name' => 'Le nom du controller, c\'est destination',
            'destinations' => $destinations
        ]);
        
    }

    /**
     * @Route("add/destination", name="addDestination")
     */
    public function addDestination(Request $request)
    {
        // je déclare une nouvelle destination vide
        $form = $this->createForm(DestinationType::class, new Destination());
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère les données du form
            $newDestination = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            // j'insère la nouvelle destination en BDD
            $entityManager->persist($newDestination);
            $entityManager->flush();
        } else {
            return $this->render('destination/add.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }

        return $this->redirect('/destination');
    }

     /**
     * @Route("edit/destination/{id}", name="editDestination")
     */
    public function editDestination($id, Request $request)
    {

        $destination = $this->getDoctrine()->getRepository(Destination::class)->find($id);

        // je déclare une nouvelle destination vide
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        } else {
            return $this->render('destination/add.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }

        return $this->redirect('/destination');
    }

    /**
     * @Route("/destination/{destination}", name="destinationType")
     */
    public function destinationType($destination)
    {
        return $this->render('destination/display.html.twig', [
            'destination' => $destination
        ]);
    }
    
}

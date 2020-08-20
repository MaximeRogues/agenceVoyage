<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends AbstractController
{
    /**
     * @Route("/destination", name="destination")
     */
    public function index()
    {
        // envoie une liste de destinations à index.html
        $destinations = ['soleil', 'montagne', 'campagne'];

        return $this->render('destination/index.html.twig', [
            'controller_name' => 'Le nom du controller, c\'est destination',
            'destinations' => $destinations
        ]);
        
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

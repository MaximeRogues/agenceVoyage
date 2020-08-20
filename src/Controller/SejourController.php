<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SejourController extends AbstractController
{
    /**
     * @Route("/sejour", name="sejour")
     */
    public function index()
    {
        return $this->render('sejour/index.html.twig', [
            'controller_name' => 'Le nom du controller, c\'est sejour',
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
    }}

<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/activity", name="activity")
     */
    public function index()
    {
        // POUR AFFICHER TOUTES MES ACTIVITEES DE MA BASE DE DONNEE
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();

        return $this->render('activity/index.html.twig', [
            'activities' => $activities,
        ]);
    }

    /**
     * @Route("/add/activity", name="addActivity")
     */
    public function addActivity()
    {

        $entityManager = $this->getDoctrine()->getManager();
        $activity = new Activity();
        $activity->setNom('Basket');

        $entityManager->persist($activity);
        $entityManager->flush();
        
        return $this->redirect('/activity');
    }

    /**
     * @Route("/activity/{id}", name="detailActivity")
     */
    public function detailsActivity($id)
    {

        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);

        return $this->render('activity/detail.html.twig', [
            'activity' => $activity,
        ]);
    }

     /**
     * @Route("/update/activity/{id}", name="updateActivity")
     */
    public function updateActivity($id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        $activity->setNom('Basket Modifié');

        $entityManager->flush();
        
        return $this->redirect('/activity');
    }

    /**
     * @Route("/delete/activity/{id}", name="deleteActivity")
     */
    public function deleteActivity($id)
    {

        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($activity);
        $entityManager->flush();

        return $this->redirect('/activity');
    }


}

<?php

namespace App\Controller;

use App\Entity\Activity;
use Doctrine\Common\Collections\Expr\Value;
use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class ActivityController extends AbstractController
{


    /**
     * @Route("/activity", name="activity")
     * @Route("/activity/{page}", name="activityPaginate")
     */
    public function index($page = 1)
    {
        // POUR AFFICHER TOUTES MES ACTIVITEES DE MA BASE DE DONNEE
        $activityRepo = $this->getDoctrine()->getRepository(Activity::class);
        $activities = $activityRepo->findAll();
        
        $nbPerPage = 2;
        $nbActivities = $activityRepo->count([]);
        $nbPage = $nbActivities / $nbPerPage;
        $nbPage = ceil($nbPage);

        return $this->render('activity/index.html.twig', [
            'activities' => $activities,
            'nbPage' => $nbPage,
            'page' => $page
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
        $activity->setNom('Handball');

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

    /**
    * @Route("/activity/search/{string}", name="searchActivity")
    */
    public function search($string){

    $activityRepository = $this->getDoctrine()->getRepository(Activity::class);
    $activities = $activityRepository->search($string);
    return $this->render('activity/search.html.twig', [
        'activities' => $activities,
        'searchString' => $string
        ]);
    }

   
    

}

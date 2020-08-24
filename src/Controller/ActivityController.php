<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use Doctrine\Common\Collections\Expr\Value;
use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $nbPerPage = 3;
        $nbActivities = $activityRepo->count([]);
        $nbPage = $nbActivities / $nbPerPage;
        $nbPage = ceil($nbPage);

        $activities = $activityRepo->paginate($nbPerPage, $page);

        return $this->render('activity/index.html.twig', [
            'activities' => $activities,
            'nbPage' => $nbPage,
            'page' => $page
        ]);
    }

    /**
     * @Route("/add/activity", name="addActivity")
     */
    public function addActivity(Request $request)
    {
        // je déclare une nouvelle activity vide
        $form = $this->createForm(ActivityType::class, new Activity());
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère les données du form
            $newActivity = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            // j'insère la nouvelle activity en BDD
            $entityManager->persist($newActivity);
            $entityManager->flush();
        } else {
            return $this->render('activity/add.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }

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

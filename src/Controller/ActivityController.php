<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use Doctrine\Common\Collections\Expr\Value;
use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\String\Slugger\SluggerInterface;


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
    public function addActivity(Request $request, SluggerInterface $slugger)
    {
        $directory = 'images/';

        // je déclare une nouvelle activity vide
        $form = $this->createForm(ActivityType::class);
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère les données du form
            $newActivity = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
    
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $directory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imagename' property to store the PDF file name
                // instead of its contents
                $newActivity->setImage($newFilename);
            }
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
     * @Route("/edit/activity/{activity}", name="editActivity")
     */
    public function editActivity(Activity $activity, Request $request, SluggerInterface $slugger)
    {
        $directory = 'images/';
        $originalImage = $activity->getImage();

        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            $activity = $form->getData();
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image && $originalImage) {
                $image->move(
                    $directory,
                    $originalImage
                );
                $activity->setImage($originalImage);

            } elseif($image && !$originalImage) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $directory,
                    $newFilename
                );
                $activity->setImage($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
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

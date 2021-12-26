<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Serializer\Encoder\JsonEncoder;

use App\Entity\Evenement;
use App\Entity\Tournoi;
use App\Entity\Equipe;
use App\Entity\Jeu;
use App\Entity\Tour;
use App\Entity\TypeJeu;
use App\Entity\NiveauJeuer;
use App\Entity\Club;
use App\Entity\Poule;

class AdminController extends AbstractController
{
    //Pour modifier les evenements
    #[Route('/admin/gestion', name: 'admin_gestion', methods: ['GET'])]
    public function main(): Response{
        $events = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

        return $this->render('/admin/gestions.html.twig', [
            'events' => $events
        ]);
    }
    //Pour create nouveau événements
    #[Route('/admin/gestion/new/evenement', name: 'admin_new_event', methods: ['GET'])]
    public function newEvent(): Response{
        $typeJeux = $this->getDoctrine()->getRepository(TypeJeu::class)->findAll();
        return $this->render('/admin/event-create.html.twig', [
            'title' => 'Créer nouveau événement',
            'types' => $typeJeux
        ]);
    }
    //Pour modifier Evenement - Tournois
    #[Route('/admin/gestion/edit/{id<\d+>?}', name: 'evenement_edit')]
    public function getEventDetails($id): Response{

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM App:Evenement e WHERE e.id=?1');
        $query->setParameter(1, $id);
        $event = $query->getSingleResult();

        $query_type = $em->createQuery('SELECT t FROM App:TypeJeu e');
        $types = $query->getArrayResult();

        $tournois = $event->getTournois();
        $tournoi = [];
        
        for ($i=0; $i<count($tournois); $i++) {
            array_push($tournoi, $tournois[$i]);
        }
        return $this->render('/admin/event-edit.html.twig', [
            'event' => $event,
            'tournois' => $tournois,
            'types' => $types
        ]);
    }
    //Pour modifier Tournoi - Tours
    #[Route('/admin/gestion/edit/tournoi/{id<\d+>?}', name: 'tournoi_edit')]
    public function getTournoiDetails($id): Response{
        $tournoi = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $tours = $tournoi->getTours();
        $toursArray = [];
        
        for ($i=0; $i<count($tours); $i++) {
            array_push($toursArray, $tours[$i]);
        }
        return $this->render('/admin/tournoi-edit.html.twig', [
            'tournoi' => $tournoi,
            'tours' => $toursArray
        ]);
    }
    //Pour modifier Tour - Poule - Match
    #[Route('/admin/gestion/edit/tour/{id<\d+>?}', name: 'tour_edit')]
    public function getTourDetails($id): Response{
        $tour = $this->getDoctrine()->getRepository(Tour::class)->find($id);
        $poules = $tour->getPoules();
        $poulesArray = [];
        
        for ($i=0; $i<count($poules); $i++) {
            array_push($poulesArray, $poules[$i]);
        }
        return $this->render('/admin/tour-edit.html.twig', [
            'tour' => $tour,
            'poules' => $poulesArray
        ]);
    }

    //[AJAX] Récupérer tous les événement
    #[Route('/admin/get/evenements', name: 'admin_evenement', methods: ['GET'])]
    public function getEvenements(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT ev FROM App:Evenement ev');
        $events = $query->getArrayResult();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($events));

        return $response;
    }
    //[AJAX] Create Evenement
    #[Route('/admin/create/event', name: 'admin_create_event', methods: ['POST'])]
    public function createEvent(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $nomEvent = $dataReceived['nomEvent'];
        $dateDebut = DateTime::createFromFormat('Y-m-d',$dataReceived['dateDebut']);
        $dateTermn = DateTime::createFromFormat('Y-m-d',$dataReceived['dateTermn']);
        $typeJeuId = $dataReceived['typeJeuId'];
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT t FROM App:TypeJeu t WHERE t.id=?1');
        $query->setParameter(1, $typeJeuId);
        $typeJeu = $query->getSingleResult();
        
        $event = new Evenement();
        $event->setDateDebut($dateDebut);
        $event->setDateTermn($dateTermn);
        $event->addType($typeJeu);
        $event->setNomEv($nomEvent);

        $em->persist($event);
        $em->flush();

        $reponseContents = json_encode(['created' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }
    //[AJAX] Update Evenement
    #[Route('/admin/update/event/{id<\d+>?}', name: 'admin_update_event', methods: ['PATCH'])]
    public function updateEvent(Request $request, $id): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $nomEvent = $dataReceived['nomEvent'];
        $dateDebut = DateTime::createFromFormat('Y-m-d',$dataReceived['dateDebut']);
        $dateTermn = DateTime::createFromFormat('Y-m-d',$dataReceived['dateTermn']);
        $typeJeuId = $dataReceived['typeJeuId'];
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM App:Evenement e WHERE e.id=?1');
        $query->setParameter(1, $id);
        $event = $query->getSingleResult();

        $query_type = $em->createQuery('SELECT t FROM App:TypeJeu t WHERE t.id=?1');
        $query_type->setParameter(1, $typeJeuId);
        $typeJeu = $query_type->getSingleResult();

        $event->setDateDebut($dateDebut);
        $event->setDateTermn($dateTermn);
        $event->addType($typeJeu);
        $event->setNomEv($nomEvent);
        
        $em->flush();

        $reponseContents = json_encode(['updated' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }

    //AJAX Delete Evenement
    #[Route('/admin/delete/event/{id<\d+>?}', name: 'admin_delete_event', methods: ['DELETE'])]
    public function deleteEvenement($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM App:Evenement e WHERE e.id = ?1');
        $query->setParameter(1, $id);
        $event = $query->getSingleResult();
       
        foreach ($event->getTournois() as $tournoi){
            $event->removeTournoi($tournoi);
        }
        $em->remove($event);
        $em->flush();

        $reponseContents = json_encode(['deleted' => true]);
        $reponse = $this->createReponse(202, $reponseContents);
        return $reponse;
    }

    //[AJAX] Récupérer les informations d'un evenement par son ID
    #[Route('/admin/get/evenement/{id<\d+>?}', name: 'admin_tournoi', methods: ['GET'])]
    public function getTournoisByEvenement($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT tn FROM App:Evenement ev, App:Tournoi tn WHERE tn.ev = ev.id AND ev.id=?1');
        $query->setParameter(1, $id);
        $tournois = $query->getArrayResult();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($tournois));

        return $response;
    }

    //[AJAX] Récupérer les informations d'un tournoi par son ID
    #[Route('/admin/get/tournoi/{id<\d+>?}', name: 'admin_tour', methods: ['GET'])]
    public function getToursByTournoi($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT tour FROM App:Tour tour, App:Tournoi tn WHERE tour.tournoi = tn.id AND tn.id = ?1');
        $query->setParameter(1, $id);
        $tours = $query->getArrayResult();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($tours));

        return $response;
    }
    //[AJAX] Récupérer les tournoi par ses ID
    #[Route('/admin/get/tour/{id<\d+>?}', name: 'admin_poule', methods: ['GET'])]
    public function getPoulesByTour($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p FROM App:Tour t, App:Poule p WHERE t.id = p.tour AND t.id = ?1');
        $query->setParameter(1, $id);
        $poules = $query->getArrayResult();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($poules));

        return $response;
    }
    //[AJAX] Récupérer les jeux par poule
    #[Route('/admin/get/poule/{id<\d+>?}', name: 'admin_jeu', methods: ['GET'])]
    public function getJeuxByPoule($id): Response
    {
        $poule = $this->getDoctrine()->getRepository(Poule::class)->find($id);
        
        $jeux = $poule->getJeus();
        $arrayJeux = array();
        foreach($jeux as $jeu){
            unset($arrayJeu);
            $arrayJeu[] = array();
            array_push($arrayJeu, ['id' => $jeu->getId(),
            'nomJeu' => $jeu->getNomJeu(),
            'pointEqA' => $jeu->getPointEqA(),
            'pointEqB' => $jeu->getPointEqB()]);
            foreach($jeu->getEquipeA() as $equipA){
                unset($arrayEquipeA);
                $arrayEquipeA[] = array('equipeA'=>['id'=>$equipA->getId(), 'nomEquipe'=>$equipA->getNomEquipe()]);
                array_push($arrayJeu, $arrayEquipeA);
            }
            foreach($jeu->getEquipeB() as $equipB){
                unset($arrayEquipeB);
                $arrayEquipeB[] = array('equipeB'=>['id'=>$equipB->getId(), 'nomEquipe'=>$equipB->getNomEquipe()]);
                array_push($arrayJeu, $arrayEquipeB);
            }
            array_push($arrayJeux, $arrayJeu);
        }
        

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($arrayJeux));

        return $response;
    }
    //[AJAX] Récupérer les clubs
    #[Route('/admin/get/clubs', name: 'admin_get_club', methods: ['GET'])]
    public function getClubs(): Response{
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT cl FROM App:Club cl');
        $clubs = $query->getArrayResult();
        $response = $this->createReponse(200, json_encode($clubs));
        return $response;
    }
    //[AJAX] Récupérer les equip
    #[Route('/admin/get/clubs/{id<\d+>?}', name: 'admin_get_equipe_by_club', methods: ['GET'])]
    public function getEquipesByClub($id): Response{
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT eq FROM App:Club cl, App:Equipe eq WHERE eq.club = cl.id AND cl.id = ?1');
        $query->setParameter(1, $id);
        $equipes = $query->getArrayResult();
        $response = $this->createReponse(200, json_encode($equipes));
        return $response;
    }
    //[AJAX] Ajouter equipe au tournoi
    #[Route('/admin/tournoi/equipe/add', name: 'admin_add_equipe_to_tournoi', methods: ['PATCH'])]
    public function addEquipeToTournoi(Request $request): Response{
        $em = $this->getDoctrine()->getManager();
        $dataReceived = json_decode($request->getContent(), true);
        $tournoiId = $dataReceived['tournoiId'];
        $equipeId = $dataReceived['equipeId'];

        //Query
        $query_tournoi = $em->createQuery('SELECT tn FROM App:Tournoi tn WHERE tn.id=?1');
        $query_tournoi->setParameter(1, $tournoiId);
        $tournoi = $query_tournoi->getSingleResult();

        $query_equipe = $em->createQuery('SELECT eq FROM App:Equipe eq WHERE eq.id=?1');
        $query_equipe->setParameter(1, $equipeId);
        $equipe = $query_equipe->getSingleResult();

        $equipe->setTournoi($tournoi);
        $this->pushEntity($equipe, 'POST');

        $reponseContents = json_encode(['updated' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }
    //[AJAX] Delete equipe au tournoi
    #[Route('/admin/tournoi/equipe/delete', name: 'admin_delete_equipe_to_tournoi', methods: ['DELETE'])]
    public function deleteEquipeFromTournoi(Request $request): Response{
        $em = $this->getDoctrine()->getManager();
        $dataReceived = json_decode($request->getContent(), true);
        $tournoiId = $dataReceived['tournoiId'];
        $equipeId = $dataReceived['equipeId'];

        //Query
        $query_tournoi = $em->createQuery('SELECT tn FROM App:Tournoi tn WHERE tn.id=?1');
        $query_tournoi->setParameter(1, $tournoiId);
        $tournoi = $query_tournoi->getSingleResult();

        $query_equipe = $em->createQuery('SELECT eq FROM App:Equipe eq WHERE eq.id=?1');
        $query_equipe->setParameter(1, $equipeId);
        $equipe = $query_equipe->getSingleResult();

        
        $tournoi->removeEquipe($equipe);

        $em->flush();

        $reponseContents = json_encode(['deleted' => true]);
        $reponse = $this->createReponse(202, $reponseContents);
        return $reponse;

    }

    //[AJAX] Créer un tournoi
    #[Route('/admin/tournoi', name: 'admin_create_tournoi', methods: ['POST'])]
    public function createTournoi (Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $nomTournoi = $dataReceived['nomTournoi'];
        $evId = $dataReceived['evId'];
        $tournoi = new Tournoi();
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find($evId);
        $tournoi->setEv($event);
        $tournoi->setNomTournoi($nomTournoi);
        
        $this->pushEntity($tournoi, 'POST');

        $reponseContents = json_encode(['created' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }
    //[AJAX] Supprimer un tournoi
    #[Route('/admin/tournoi', name: 'admin_delete_tournoi', methods: ['DELETE'])]
    public function deleteTournoi (Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $tournoiId = $dataReceived['tournoiId'];
        $eventId = $dataReceived['eventId'];
        
        //Ici on ne peut pas appler 2 doctrine manager dans même temps donc, on appler un manager puis utilise les requêtes pour
        //récupérer les entities
        $em = $this->getDoctrine()->getManager();

        $query_event = $em->createQuery('SELECT ev FROM App:Evenement ev WHERE ev.id=?1');
        $query_event->setParameter(1, $eventId);
        $event = $query_event->getSingleResult();

        $query_tournoi = $em->createQuery('SELECT tn FROM App:Tournoi tn WHERE tn.id=?1');
        $query_tournoi->setParameter(1, $tournoiId);
        $tournoi = $query_tournoi->getSingleResult();
        
        $event->removeTournoi($tournoi);
        
        $em->remove($tournoi);
        $em->flush();
        
        $reponseContents = json_encode(['deleted' => true]);
        
        $reponse = $this->createReponse(202, $reponseContents);
        return $reponse;

    }
    //[AJAX] Créer un tour
    #[Route('/admin/tour', name: 'admin_create_tour', methods: ['POST'])]
    public function createTour (Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $nomTour = $dataReceived['nomTour'];
        $tournoiId = $dataReceived['tournoiId'];
        $tour = new Tour();
        $tournoi = $this->getDoctrine()->getRepository(Tournoi::class)->find($tournoiId);
        $tour->setTournoi($tournoi);
        $tour->setNomTour($nomTour);
        
        $this->pushEntity($tour, 'POST');

        $reponseContents = json_encode(['created' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }
    //[AJAX] Updated les équipes dans un tour
    #[Route('/admin/tour/{id}', name: 'admin_delete_tour', methods: ['PATCH', 'DELETE'])]
    public function updateEquipe(Request $request, $id): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $tourId = $id;
        if ($request->isMethod('PATCH')){
            return $this->addEquipesTour($dataReceived, $tourId);
        }
        if ($request->isMethod('DELETE')){
            return $this->removeTour($dataReceived, $tourId);
        }
        
    }

    //[AJAX] Générer les jeux.
    #[Route('/admin/poule', name: 'admin_create_jeux', methods: ['POST'])]
    public function createJeux(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        foreach($dataReceived as $data){
            $pouleID = $data[0]['idPoule'];
            $listJeux = $data[1]['jeux'];
            foreach($listJeux as $jeu){
                $equipeAId = $jeu['equipeA'];
                $equipeBId = $jeu['equipeB'];

                $em = $this->getDoctrine()->getManager();
                $query_equipe = $em->createQuery('SELECT eq FROM App:Equipe eq WHERE eq.id=?1');
                $query_poule = $em->createQuery('SELECT p FROM App:Poule p WHERE p.id=?2');
                //Find Equipe A
                $query_equipe->setParameter(1, $equipeAId);
                $equipeA = $query_equipe->getSingleResult();
                //Find Equipe B
                $query_equipe->setParameter(1, $equipeBId);
                $equipeB = $query_equipe->getSingleResult();
                //Find Poule
                $query_poule->setParameter(2, $pouleID);
                $poule = $query_poule->getSingleResult();
                //Nouveu jeu
                $newJeu = new Jeu();
                $newJeu->setNomJeu($equipeA->getNomEquipe() . "-" . $equipeB->getNomEquipe());
                $newJeu->addEquipeA($equipeA);
                $newJeu->addEquipeB($equipeB);
                $newJeu->setPoule($poule);
                $newJeu->setPointEqA(0);
                $newJeu->setPointEqB(0);

                //Push to database
                $em->persist($newJeu);
                $em->flush();
                
            }

        }
        $reponseContents = json_encode(['created' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;
    }

    //[AJAX] Delete les poules
    #[Route('/admin/delete/poule', name: 'admin_delete_poule', methods: ['DELETE'])]
    public function deletePoules(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        foreach ($dataReceived as $pouleID){
            $em = $this->getDoctrine()->getManager(); 
            $query_poule = $em->createQuery('SELECT p FROM App:Poule p WHERE p.id=?1');
            $query_poule->setParameter(1, $pouleID);
            $poule = $query_poule->getSingleResult();

            foreach ($poule->getJeus() as $jeu){
                $poule->removeJeu($jeu);
            }

            $em->remove($poule);
            $em->flush();
        }

        $reponseContents = json_encode(['deleted' => true]);
        $reponse = $this->createReponse(202, $reponseContents);
        return $reponse;
    }
    //[AJAX] Create poule
    #[Route('/admin/create/poule', name: 'admin_create_poule', methods: ['POST'])]
    public function createPoules(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $nomPoule = $dataReceived['nomPoule'];
        $tourId = $dataReceived['tourId'];

        $em = $this->getDoctrine()->getManager();
        $query_tour = $em->createQuery('SELECT t FROM App:Tour t WHERE t.id=?1');
        
        //Find Tour
        $query_tour->setParameter(1, $tourId);
        $tour = $query_tour->getSingleResult();

        //Create Poule
        $poule = new Poule();
        $poule->setNomPoule($nomPoule);
        $poule->setTour($tour);

        $em->persist($poule);
        $em->flush();

        $reponseContents = json_encode(['created' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }

    //AJAX Update equipes dans les poules.
    #[Route('/admin/update/poule', name: 'admin_update_poule', methods: ['PATCH'])]
    public function updatePoules(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        foreach($dataReceived as $data){
            $pouleID = $data[0]['idPoule'];
            $listEquipes = $data[1]['equipes'];

            $em = $this->getDoctrine()->getManager();
            //Find Poule
            $query_poule = $em->createQuery('SELECT p FROM App:Poule p WHERE p.id=?2');
            $query_poule->setParameter(2, $pouleID);
            $poule = $query_poule->getSingleResult();

            foreach($listEquipes as $equipeId){
                
                $query_equipe = $em->createQuery('SELECT eq FROM App:Equipe eq WHERE eq.id=?1');
                //Find Equipe
                $query_equipe->setParameter(1, $equipeId);
                $equipe = $query_equipe->getSingleResult();
                
                $poule->addEquipe($equipe);
                
            }
            //Push to database
            $em->flush();

        }
        $reponseContents = json_encode(['created' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;

    }

    //AJAX Update Points des jeux
    #[Route('/admin/jeu/{id}', name: 'admin_update_point_jeu', methods: ['PATCH'])]
    public function updatePointJeu($id, Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $pointEqA = $dataReceived['pointEqA'];
        $pointEqB = $dataReceived['pointEqB'];
        $em = $this->getDoctrine()->getManager();
        //Find Jeu
        $query_jeu = $em->createQuery('SELECT j FROM App:Jeu j WHERE j.id=?1');
        $query_jeu->setParameter(1, $id);
        $jeu = $query_jeu->getSingleResult();
        
        $jeu->setPointEqA($pointEqA);
        $jeu->setPointEqB($pointEqB);

        $em->flush();

        $reponseContents = json_encode(['updated' => true]);
        $reponse = $this->createReponse(201, $reponseContents);
        return $reponse;
        
    }

    // Inscription les clubs et équipes
    #[Route('/admin/inscription', name: 'inscription')]
    public function index(): Response
    {
        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();
        return $this->render('/admin/inscription-equipe.html.twig', [
            'title' => 'Incription',
            'clubs' => $clubs,
        ]);
    }

    

    #[Route('/admin/create/club', name: 'admin_create_club', methods: ['POST'])]
    public function createClub(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $nomClub = $dataReceived['nomClub'];
        $club = new Club();
        $club->setNomClub($nomClub);

        $em = $this->getDoctrine()->getManager(); 
        $em->persist($club);
        $em->flush();
        
        
        $content = json_encode(['created' => true]);
        $response = $this->createReponse(201, $content);

        return $response;
    }

    #[Route('/admin/create/equipe', name: 'admin_create_equipe', methods: ['POST'])]
    public function createEquipe(Request $request): Response{
        $dataReceived = json_decode($request->getContent(), true);
        $idClub = $dataReceived['clubId'];
        $nomEquipe = $dataReceived['nomEquipe'];
        $equipe = new Equipe();
        $equipe->setNomEquipe($nomEquipe);

        $em = $this->getDoctrine()->getManager(); 
        $query = $em->createQuery('SELECT c FROM App:Club c WHERE c.id=?1');
        $query->setParameter(1, $idClub);
        $club = $query->getSingleResult();

        $equipe->setClub($club);

        $em->persist($equipe);
        $em->flush();
        
        
        $content = json_encode(['created' => true]);
        $response = $this->createReponse(201, $content);

        return $response;
    }
    /////////// Function pour HTTP REQUEST ////////////
    public function addEquipesTour($dataReceived, $tourId){
        $dataReceived = $dataReceived['eqIDs'];
        $em = $this->getDoctrine()->getManager();
        $query_tour = $em->createQuery('SELECT t FROM App:Tour t WHERE t.id=?1');
        $query_tour->setParameter(1, $tourId);
        $tour = $query_tour->getSingleResult();

        for ($i = 0; $i<count($dataReceived); $i++){
            $query_equipe = $em->createQuery('SELECT e FROM App:Equipe e WHERE e.id=?1');
            $query_equipe->setParameter(1, $dataReceived[$i]);
            $equipe = $query_equipe->getSingleResult(); 

            $tour->addEquipe($equipe);
        }

        $em->flush();

        $reponseContents = json_encode(['updated' => true]);
        $reponse = $this->createReponse(201, $reponseContents);

        return $reponse;
    }
    public function removeTour($dataReceived, $tourId){
        $tournoiId = $dataReceived['tournoiId'];  
        //Ici on ne peut pas appler 2 doctrine manager dans même temps donc, on appler un manager puis utilise les requêtes pour
        //récupérer les entities
        $em = $this->getDoctrine()->getManager();

        $query_tour = $em->createQuery('SELECT t FROM App:Tour t WHERE t.id=?1');
        $query_tour->setParameter(1, $tourId);
        $tour = $query_tour->getSingleResult();

        $query_tournoi = $em->createQuery('SELECT tn FROM App:Tournoi tn WHERE tn.id=?1');
        $query_tournoi->setParameter(1, $tournoiId);
        $tournoi = $query_tournoi->getSingleResult();
        
        $tournoi->removeTour($tour);
        
        $em->remove($tour);
        $em->flush();
        
        $reponseContents = json_encode(['deleted' => true]);
        
        $reponse = $this->createReponse(202, $reponseContents);
        return $reponse;
    }

    public function pushEntity($entity, $methode){
        $manger = $this->getDoctrine()->getManager();
        if ($methode == 'POST'){
            $manger->persist($entity);
            $manger->flush();
        }
        if ($methode == 'DELETE'){
            $manger->remove($entiy);
            $manger->flush();
        }
        
    }

    public function createReponse($stt, $content): Response{
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($content);
        $response->setStatusCode($stt);
        return $response;
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use App\Entity\Tournoi;
use App\Entity\Equipe;
use App\Entity\Jeu;
use App\Entity\Tour;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'evenement')]
    public function getEvenements(): Response
    {

        $events = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

        return $this->render('/evenement/list-evenement.html.twig', [
            'events' => $events
        ]);
    }
    
    #[Route('/evenement/{id<\d+>?}', name: 'evenement_details')]
    public function getEventDetails($id): Response{
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $tournois = $event->getTournois();
        $tournoi = [];
        
        for ($i=0; $i<count($tournois); $i++) {
            array_push($tournoi, $tournois[$i]);
        }
        
        return $this->render('/evenement/event-details.html.twig', [
            'event' => $event,
            'tournois' => $tournois
        ]);
    }
    
}

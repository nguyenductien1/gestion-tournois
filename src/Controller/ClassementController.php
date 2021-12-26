<?php

namespace App\Controller;

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

class ClassementController extends AbstractController
{
    #[Route('/classement', name: 'classement_render', methods: ['GET'])]
    public function index(Request $request): Response{
        return $this->render('/evenement/classement.html.twig', [
            'title'=>'Classement des tournois',
            'events'=>$this->getDoctrine()->getRepository(Evenement::class)->findAll(),
        ]);
    }
    
    #[Route('/classement/data/{id}/', name: 'classement', methods: ['GET'])]
    public function getData($id): Response
    {
        $tourId = $id;

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT t FROM App:Tour t WHERE t.id=?1');
        $query->setParameter(1, $tourId);
        $tour = $query->getSingleResult();
        
        //Les poules dans $tour
        $poules = $tour->getPoules();
        if (count($poules) > 0){
            $poulesArray = [];
            foreach ($poules as $poule ){
                $jeux = $poule->getJeus();
                $jeuxArray = [];
                $equipesArray = [];
                if (count($jeux) > 0){
                    foreach ($jeux as $jeu) {
                        foreach ($jeu->getEquipeA() as $equipeA){
                            $nomEquipeA = $equipeA->getNomEquipe();
                            $idEquipeA = $equipeA->getId();
                        }

                        $pointEquipeA = $jeu->getPointEqA();
                        $pointEquipeB = $jeu->getPointEqB();
                        foreach ($jeu->getEquipeB() as $equipeB){
                            $nomEquipeB = $equipeB->getNomEquipe();
                            $idEquipeB = $equipeB->getId();
                        }
                        array_push($jeuxArray, 
                        [
                            'idEquipe1'=>$idEquipeA,
                            'idEquipe2' => $idEquipeB,
                            'pointEquipe1' => $pointEquipeA,
                            'pointEquipe2' => $pointEquipeB
                        ]
                        );
                        if (!in_array($idEquipeA, $equipesArray)){
                            array_push($equipesArray, $idEquipeA);
                        }
                        if (!in_array($idEquipeB, $equipesArray)){
                            array_push($equipesArray, $idEquipeB);
                        }
                    }
                }
            //array_push($poulesArray, ['pouleId'=>$poule->getId(), 'jeux'=> $jeuxArray]);
            array_push($poulesArray, ['pouleId'=>$poule->getId(), 'jeux'=> $jeuxArray, 'equipes' => $equipesArray]);
            
            }

            $resultats_tours = [];
            foreach ($poulesArray as $pouleArray){

                $jeux_classe = $pouleArray['jeux'];
                $resultats_poules = [];
                $count_gagne = [];
                $count_perdu = [];
                foreach ($jeux_classe as $jeu_classe) {
                    if($jeu_classe['pointEquipe1'] > $jeu_classe['pointEquipe2']){
                        array_push($count_gagne, ['idEquipe'=>$jeu_classe['idEquipe1'], 'gagne'=>1, 'set_gagne'=>$jeu_classe['pointEquipe1'], 'set_perdu'=>$jeu_classe['pointEquipe2']]);
                        array_push($count_perdu, ['idEquipe'=>$jeu_classe['idEquipe2'], 'perdu'=>1, 'set_gagne'=>$jeu_classe['pointEquipe2'], 'set_perdu'=>$jeu_classe['pointEquipe1']]);
                    }
                    elseif ($jeu_classe['pointEquipe1'] < $jeu_classe['pointEquipe2']){
                        array_push($count_gagne, ['idEquipe'=>$jeu_classe['idEquipe2'], 'gagne'=>1, 'set_gagne'=>$jeu_classe['pointEquipe2'], 'set_perdu'=>$jeu_classe['pointEquipe1']]);
                        array_push($count_perdu, ['idEquipe'=>$jeu_classe['idEquipe1'], 'perdu'=>1, 'set_gagne'=>$jeu_classe['pointEquipe1'], 'set_perdu'=>$jeu_classe['pointEquipe2']]);

                    }
                }
                array_push($resultats_poules, ['gagne'=>$count_gagne, 'perdu'=>$count_perdu]);
                array_push($resultats_tours, ['pouleID'=>$pouleArray['pouleId'], 'gagne'=>$count_gagne, 'perdu'=>$count_perdu, 'equipes'=>$pouleArray['equipes']]);
            }

            $results_poules = [];
            foreach ($resultats_tours as $resultat_poule){
                
                $pouleId = $resultat_poule['pouleID'];
                $gagne_equipe = [];
                $perdu_equipe = [];
                
                foreach ($resultat_poule['equipes'] as $equipe){
                    $gagne_count = 0;
                    $perdu_count = 0;
                    $set_gagne_count = 0;
                    $set_perdu_count = 0;
                    foreach ($resultat_poule['gagne'] as $gagne){
                        if ($gagne['idEquipe'] == $equipe){
                            $gagne_count = $gagne_count + 1;
                            $set_gagne_count = $set_gagne_count + $gagne['set_gagne'];
                            $set_perdu_count = $set_perdu_count + $gagne['set_perdu'];
                        }
                    }
                    array_push($gagne_equipe, ['idEquipe'=>$equipe, 'gagne'=>$gagne_count,'set_gagne'=>$set_gagne_count, 'set_perdu'=>$set_perdu_count]);

                    foreach ($resultat_poule['perdu'] as $gagne){
                        if ($gagne['idEquipe'] == $equipe){
                            $perdu_count = $perdu_count + 1;
                            $set_gagne_count = $set_gagne_count + $gagne['set_gagne'];
                            $set_perdu_count = $set_perdu_count + $gagne['set_perdu'];
                        }
                    }
                    array_push($perdu_equipe, ['idEquipe'=>$equipe, 'perdu'=>$perdu_count,'set_gagne'=>$set_gagne_count, 'set_perdu'=>$set_perdu_count]);
                    
                }
                array_push($results_poules, ['pouleID'=>$pouleId, "gagne"=>$gagne_equipe, "perdu"=>$perdu_equipe]);
            }
          
            $poule_final_result = [];
            foreach ($results_poules as $resultsPoule){
                $pouleId = $resultsPoule['pouleID'];
                $result_equipe = [];
                foreach ($resultsPoule['gagne'] as $gagne){
                    foreach ($resultsPoule['perdu'] as $perdu){
                        if ($gagne['idEquipe']==$perdu['idEquipe']){
                            $set_gagne = $gagne['set_gagne']+$perdu['set_gagne'];
                            $set_perdu = $gagne['set_perdu']+$perdu['set_perdu'];
                            $diff_set  = $set_gagne - $set_perdu;
                            $point = $gagne['gagne'] * 3;
                            $jeux = $gagne['gagne'] + $perdu['perdu'];
                            $perdu_n = $perdu['perdu'];
                        }
                    }
                    array_push($result_equipe, [
                        'idEquipe'=>$gagne['idEquipe'],
                        'nomEquipe'=>$this->getEquipeName($gagne['idEquipe']),
                        'jeux'=>$jeux,
                        'point'=>$point,
                        'gagne'=>$gagne['gagne'],
                        'perdu'=>$perdu_n,
                        'set_gagne'=>$set_gagne,
                        'set_perdu'=>$set_perdu,
                        'diff'=>$diff_set
                    ]);
                }
                array_push($poule_final_result, ['pouleID'=>$pouleId,'pouleName'=> $this->getPouleName($pouleId), 'results'=>$result_equipe]);

            }



        }
        $content = json_encode($poule_final_result);
        $response = $this->createReponse(200, $content);
        
        return $response;
    }

     //[AJAX] Récupérer les informations d'un evenement par son ID
     #[Route('/classement/evenement/{id<\d+>?}', name: 'classement_tournoi', methods: ['GET'])]
     public function getTournoisByEvenement($id): Response
     {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery('SELECT tn FROM App:Evenement ev, App:Tournoi tn WHERE tn.ev = ev.id AND ev.id=?1');
         $query->setParameter(1, $id);
         $tournois = $query->getArrayResult();
        
         $content = json_encode($tournois);
         $response = $this->createReponse(200, $content);
 
         return $response;
     }

     //[AJAX] Récupérer les informations d'un tournois par son ID
     #[Route('/classement/tournoi/{id<\d+>?}', name: 'classement_tour', methods: ['GET'])]
     public function getTourTrounois($id): Response
     {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery('SELECT t FROM App:Tournoi tn, App:Tour t WHERE t.tournoi = tn.id AND tn.id=?1');
         $query->setParameter(1, $id);
         $tours = $query->getArrayResult();
        
         $content = json_encode($tours);
         $response = $this->createReponse(200, $content);
 
         return $response;
     }


    function getEquipeName($idEquipe){
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($idEquipe);
        return $equipe->getNomEquipe();
    }
    function getPouleName($idPoule){
        $equipe = $this->getDoctrine()->getRepository(Poule::class)->find($idPoule);
        return $equipe->getNomPoule();
    }

    public function createReponse($stt, $content): Response{
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($content);
        $response->setStatusCode($stt);
        return $response;
    }
}

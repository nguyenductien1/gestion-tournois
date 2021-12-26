<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Jeu;
use App\Form\JeuType;

class JeuController extends AbstractController
{
    #[Route('/jeu', name: 'jeu')]
    public function createJeu(Request $request): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeu);
            $entityManager->flush();
            return $this->redirectToRoute('jeu');
        }
        return $this->render('jeu/createJeu.html.twig', ['form'=>$form->createView()]);
    }
}

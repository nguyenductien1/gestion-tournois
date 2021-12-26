<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    
    private UserPasswordEncoderInterface $passwordEncoder;

    #[Route('/auth/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $dataReceived = json_decode($request->getContent(), true);
        $email = $dataReceived[0]['value'];
        $password = $dataReceived[1]['value'];
        $passwordConfirm = $dataReceived[2]['value'];
        $data = ['email' => $email, 'password' => $password];

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');


        $userInDatabase = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($userInDatabase){

            $response->setContent(json_encode(['user_existed' => 'true']));
            $response->setStatusCode(203); //Created
            
        }
       
        if (!$userInDatabase){
            $user = $this->getDoctrine()->getRepository(User::class)->createNewUser($data);
            $response->setStatusCode(201);
            $response->setContent(json_encode(['user_created' => $user->getEmail()]));
            
        }

        return  $response;
       
    }  
    
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movement;
use App\Form\MovementFormType;
use App\Repository\UserRepository;
use App\Repository\MovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{

    #[Route('/user/{id}', name: 'app_user')]
        public function index(Request $request, MovementRepository $movementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, User $user, Movement $movement): Response
        {
            
            // dd($request);
            // $movements = $movementRepository->findBy(array('user_id' => 1));

            // dd($user);

            // $movements = $movementRepository->findAll();

            // $movements = $movementRepository->findBy(array('user_id' => 1));

            // dd($request);
            

            // dd($movement);
            
            // dd($request);
            // dd($request->attributes);
            
            
            // dd($request->attributes->get('id'));
            // $requestUserId = $request->attributes->get('id');
            // dd($user);
            // $this->denyAccessUnlessGranted('USER_VIEW', $requestUserId);
            
            $userId = $user->getId();
            // dd($movement->getId());
            // if $user->getId() === $movement->getId()

            // dd($movementRepository);
            // Vérifier si des mouvements ont déjà été enregistrés id1 deconne.......
            $movements = $movementRepository->findBy(array('user_id' => $userId));

            return $this->render('movement/index.html.twig', compact('movements'));
        }
}
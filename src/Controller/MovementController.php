<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movement;
use App\Form\MovementFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\MovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovementController extends AbstractController
{
    #[Route('/movement/add/user/{id}', name: 'app_movement_add_user')]
    public function add(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, User $user): Response
    {
        // dd($user);
        $userId = $user->getId();
        
        $movement = new Movement();
        // $form = $this->createForm(MovementFormType::class, $movement);
        
        // $movement->setUser_id($userId);
        // dd($movement);
        $form = $this->createForm(MovementFormType::class, $movement)
                    // ->add('user_id')
                    ;
        
        $form->handleRequest($request);

        // dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            // dd($form->getData());
            $form->getData()->setUser_id($user);
            $entityManager->persist($movement);
            $entityManager->flush();

            $this->addFlash('success', 'Mouvement ajouté avec succès');

            return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        }
        
        return $this->render('movement/add.html.twig', [
            'movementForm' => $form->createView(),
        ]);
        
        // return $this->render('movement/add.html.twig', [
        //     'controller_name' => 'MovementController',
        // ]);
    }


    #[Route('/movement/edit/{id}', name: 'app_movement_edit')]
    public function edit(Movement $movement, Request $request, EntityManagerInterface $entityManager): Response
    {
        // dump($movement);
        // dd($movement->getUser_Id());
        // dd($movement->getUser_Id(array('id')));
        // dd($movement->getUser_Id('id'));
        
        
        // dd($movement->getUser_Id()->getId());
        $userId = $movement->getUser_Id()->getId();

        // $userId = $user->getId();
        
        $form = $this->createForm(MovementFormType::class, $movement);
        // dd($form);
        $form->handleRequest($request);

        // dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movement);
            $entityManager->flush();

            $this->addFlash('success', 'Mouvement modifié avec succès');

            return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        }
        
        return $this->render('movement/edit.html.twig', [
            'movementForm' => $form->createView(),
            'movement' => $movement
        ]);
        
        // return $this->render('movement/add.html.twig', [
        //     'controller_name' => 'MovementController',
        // ]);
    }


    #[Route('/movement/user/{id}', name: 'app_movement_user')]
    public function index(Request $request, MovementRepository $movementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, User $user): Response
    {
        // $movements = $movementRepository->findBy(array('user_id' => 1));

        // dd($user);

        // $id = $user->getuser_id;

        // dd($id);

        // $movements = $movementRepository->findAll();

        // $movements = $movementRepository->findBy(array('user_id' => 1));

        // dd($request);
        
        $userId = $user->getId();

        $movements = $movementRepository->findBy(array('user_id' => $userId));

        return $this->render('movement/index.html.twig', compact('movements'));
    }





    // #[Route('/movement', name: 'app_movement')]
    // public function index(MovementRepository $movementRepository, UserRepository $userRepository): Response
    // {
    //     // $movements = $movementRepository->findAll();

    //     // $movements = $movementRepository->findBy(array('user_id' => '1'));
    //     // $movements = $movementRepository->findBy(array('user_id' => 1));

    //     // dd($user);

    //     // $id = $user->getuser_id;

    //     // dd($id);

        

    //     $movements = $movementRepository->findBy(array('user_id' => 1));


        
    //     // dd($movements);

    //     return $this->render('movement/index.html.twig', compact('movements'));
    // }


    #[Route('/movement/delete/{id}', name: 'app_movement_delete')]
    public function delete(Movement $movement, EntityManagerInterface $entityManager):Response
    {
        // dd($movement);
        // dd($user);
        
        // $userId = $user->getId();

        // dd($userId);

        // dd($userId = $movement->getUser_Id()->getId());
        $userId = $movement->getUser_Id()->getId();
        

        $entityManager->remove($movement);
        $entityManager->flush();

        $this->addFlash('success', 'Mouvement supprimé avec succès');

        return $this->redirectToRoute('app_movement_user', array('id' => $userId));
    }


    #[Route('/admin/movement', name: 'app_admin_movement')]
    public function indexAdmin(MovementRepository $movementRepository, UserRepository $userRepository): Response
    {
        $movements = $movementRepository->findAll();

        // dd($movements);

        return $this->render('movement/index.html.twig', compact('movements'));
    }



}

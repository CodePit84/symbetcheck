<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movement;
use Doctrine\ORM\Mapping\Id;
use App\Form\MovementFormType;
use App\Repository\UserRepository;
use App\Repository\MovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
// use PHPStan\PhpDocParser\Parser\TokenIterator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
// use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MovementController extends AbstractController
{
    #[Route('/movement/addWithdraw/user/{id}', name: 'app_movement_addWithdraw_user')]
    public function addWithdraw(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, User $user): Response
    {
        $userId = $user->getId();
        
        $movement = new Movement();
        
        $form = $this->createForm(MovementFormType::class, $movement)
                    // ->add('user_id')
                    ;
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ceci fonctionnait :
            // $form->getData()->setUser_id($user);

            $movement = $form->getData();
            $movement->setUser_id($this->getUser());

            $entityManager->persist($movement);
            $entityManager->flush();

            $this->addFlash('success', 'Mouvement ajouté avec succès');

            return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        }
        
        return $this->render('movement/addWithdraw.html.twig', [
            'movementForm' => $form->createView(),
        ]);
    }

    #[Route('/movement/addDeposit/user/{id}', name: 'app_movement_addDeposit_user')]
    public function addDeposit(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, User $user): Response
    {
        $userId = $user->getId();
        
        $movement = new Movement();
        
        $form = $this->createForm(MovementFormType::class, $movement)
                    // ->add('user_id')
                    ;
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ceci fonctionnait :
            // $form->getData()->setUser_id($user);

            $movement = $form->getData();
            $movement->setUser_id($this->getUser());

            // Pour transformer la valeur en négatif
            $prix = $movement->getMovement() * -1;
            $movement->setMovement($prix);

            $entityManager->persist($movement);
            $entityManager->flush();

            $this->addFlash('success', 'Mouvement ajouté avec succès');

            return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        }
        
        return $this->render('movement/addDeposit.html.twig', [
            'movementForm' => $form->createView(),
        ]);
    }

    #[Route('/movement/edit/{id}', name: 'app_movement_edit')]
    public function edit(Movement $movement, Request $request, EntityManagerInterface $entityManager): Response
    {
        // dump($movement);
        // dd($movement->getUser_Id());
        // dd($movement->getUser_Id(array('id')));
        // dd($movement->getUser_Id('id'));
        
        // dd($movement->getUser_Id()->getId());
        // dd($user->getId());
        
        $this->denyAccessUnlessGranted('MOVEMENT_EDIT', $movement);
        
        $userId = $movement->getUser_Id()->getId();

        // $userId = $user->getId();
        
        $form = $this->createForm(MovementFormType::class, $movement);
        $form->handleRequest($request);

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
    }

    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/movement/user/{id}', name: 'app_movement_user')]
    public function index(User $choosenUser, Request $request, MovementRepository $movementRepository, PaginatorInterface $paginator, UserRepository $userRepository, EntityManagerInterface $entityManager, Movement $movement): Response
    {
        // $movements = $movementRepository->findAll();
        // $movements = $movementRepository->findBy(array('user_id' => 1));
        // $userId = $user->getId();

        // cette ligne fonctionne :
        // $movements = $movementRepository->findBy(array('user_id' => $userId));

        // mais j'essai :
        $movementsUser = $movementRepository->findBy(['user_id' => $this->getUser()]);

        // Pour faire la somme des valeurs de tous les mouvements
        $sum = 0;
        // foreach ($movements as $key => $movement) {
        foreach ($movementsUser as $movement) {
            // dump($movement->getMovement());
            $sum = $sum + $movement->getMovement();
        }


        // Paginator pour paginer les pages des mouvements
        $movements = $paginator->paginate(
            $movementsUser, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // return $this->render('movement/index.html.twig', compact('movements'));
        
        return $this->render('movement/index.html.twig', [
            'sum' => $sum,
            'movements' => $movements
        ],
        );

    }

    #[Route('/movement/delete/{id}', name: 'app_movement_delete')]
    public function delete(Movement $movement, EntityManagerInterface $entityManager):Response
    {
        // dd($movement);
        // dd($user);
        
        // $userId = $user->getId();

        // dd($userId);

        // dd($userId = $movement->getUser_Id()->getId());

        $this->denyAccessUnlessGranted('MOVEMENT_DELETE', $movement);

        $userId = $movement->getUser_Id()->getId();
        

        $entityManager->remove($movement);
        $entityManager->flush();

        $this->addFlash('success', 'Mouvement supprimé avec succès');

        return $this->redirectToRoute('app_movement_user', array('id' => $userId));
    }

    #[Security("is_granted('ROLE_ADMIN') and user === choosenUser")]
    #[Route('/admin/allmovement', name: 'app_admin_allmovement')]
    public function indexAdmin(MovementRepository $movementRepository, UserRepository $userRepository): Response
    {
        $movements = $movementRepository->findAll();

        // dd($movements);

        return $this->render('movement/index.html.twig', compact('movements'));
    }
}

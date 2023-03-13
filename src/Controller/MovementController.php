<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movement;
use Doctrine\ORM\Mapping\Id;
use App\Form\MovementFormType;
use App\Repository\UserRepository;
use App\Repository\MovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Container4w52XSC\getSecurity_User_Provider_Concrete_AppUserProviderService;

class MovementController extends AbstractController
{
    #[Route('/movement/add/user/{id}', name: 'app_movement_add_user')]
    public function add(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, User $user): Response
    {
        $userId = $user->getId();
        
        $movement = new Movement();
        
        $form = $this->createForm(MovementFormType::class, $movement)
                    // ->add('user_id')
                    ;
        
        $form->handleRequest($request);

        // dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            // ceci fonctionnait :
            // $form->getData()->setUser_id($user);

            $movement = $form->getData();
            $movement->setUser_id($this->getUser());

            // Pour transformer la valeur en négatif
            // $prix = $movement->getMovement() * -1;
            // $movement->setMovement($prix);


            ////////////////////////////////////////

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
        // dd($user->getId());
        
        // dd($movement);
        $this->denyAccessUnlessGranted('MOVEMENT_EDIT', $movement);
        
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

    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/movement/user/{id}', name: 'app_movement_user')]
    public function index(User $choosenUser, Request $request, MovementRepository $movementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, Movement $movement): Response
    {
        
        // dd($request);
        // $movements = $movementRepository->findBy(array('user_id' => 1));

        // dd($user);

        // $movements = $movementRepository->findAll();

        // $movements = $movementRepository->findBy(array('user_id' => 1));

        // dd($request);
        // dd($user);

        // dd($movement);
        
        // dd($request->attributes);

        // dd(intval($request->attributes->get('id')));
        
        // dump($user);

        // $user = $token->getUser();
        // $requestURLid = intval($request->attributes->get('id'));
        

        // dump($requestURLid);
        // dd($user);


        // $this->denyAccessUnlessGranted('MOVEMENT_VIEW', $movement);
        // dd($app);
        // $userId = $user->getId();
        // dd($movement->getId());
        
        // if $user->getId() === $movement->getId()

        // dd($movementRepository);
        // Vérifier si des mouvements ont déjà été enregistrés id1 deconne.......
        


        // $userId = $user->getId();
        // cette ligne fonctionne :
        // $movements = $movementRepository->findBy(array('user_id' => $userId));

        // mais j'essai :
        $movements = $movementRepository->findBy(['user_id' => $this->getUser()]);

        $sum = 0;
        // foreach ($movements as $key => $movement) {
        foreach ($movements as $movement) {
            // dump($movement->getMovement());
            $sum = $sum + $movement->getMovement();
        }

        // return $this->render('movement/index.html.twig', compact('movements'));
        
        return $this->render('movement/index.html.twig', [
            'sum' => $sum,
            'movements' => $movements
        ],
        );

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

        

        // $movements = $movementRepository->findBy(array('user_id' => 1));


        
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

        $this->denyAccessUnlessGranted('MOVEMENT_DELETE', $movement);

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

    // public function sumMovement($id)
    // {
    //     // $querySumMovement = $this->createQueryBuilder('g')
    //     return $this->createQueryBuilder('c')
    //     ->select("sum(g.movement) as sumMovement")
    //     ->where('g.user_id_id = :user_id_id')
    //     ->setParameter('user_id_id', $id)
    //     ->getQuery()
    //     ->getResult()
    //     ;
    // }

}

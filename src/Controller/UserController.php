<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserFormType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user's profile
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/edit/user/{id}', name: 'app_edit_user')]
    public function editUser(User $choosenUser, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {
        // Avec le "Security" devant la "Route" les 2 conditions (if) suivantes ne sont plus nécessaires :

        // Si l'utilisateur n'est pas connecté le renvoyer sur le formulaire de connexion
        // if(!$this->getUser()) {
        //     return $this->redirectToRoute('app_login');
        // }

        // Vérification de l'utilisateur actif
        // Si l'utilisateur actif veut modifier un autre utilisateur le renvoyer sur la page d'accueil
        // if($this->getUser() !== $choosenUser){
        //     return $this->redirectToRoute('home');
        // }

        // Si tout est OK on lui passe le formulaire
        
        $form = $this->createForm(EditUserFormType::class, $choosenUser);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()->getplainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiées.'
                );

                return $this->redirectToRoute('app_movement_user', array('id' => $choosenUser->getId()));
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('registration/edit_user.html.twig', [
            'editUserForm' => $form->createView(),
        ]);
    }

    /**
     * This controller allow us to edit user's password
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/edit/password_user/{id}', 'app_edit_password_user', methods: ['GET', 'POST'])]
    public function editPassword(
        User $choosenUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword'])) {
                $choosenUser->setPassword(
                    $hasher->hashPassword(
                        $choosenUser,
                        $form->getData()['newPassword']
                        )
                );

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                $manager->persist($choosenUser);
                $manager->flush();

                return $this->redirectToRoute('app_movement_user', array('id' => $choosenUser->getId()));
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('registration/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
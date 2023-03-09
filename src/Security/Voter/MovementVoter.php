<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Movement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovementVoter extends Voter
{
    public const MOVEMENT_DELETE = 'MOVEMENT_DELETE';
    public const MOVEMENT_EDIT = 'MOVEMENT_EDIT';
    public const MOVEMENT_VIEW = 'MOVEMENT_VIEW';

    protected function supports(string $attribute, mixed $movement): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::MOVEMENT_DELETE, self::MOVEMENT_EDIT, self::MOVEMENT_VIEW])
            && $movement instanceof \App\Entity\Movement;
    }

    protected function voteOnAttribute(string $attribute, mixed $movement, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // dd($user);
        // if the user is anonymous, do not grant access
        // dd($user);
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si le mouvement a un propriétaire
        if(null === $movement->getUserId()) return false;


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::MOVEMENT_DELETE:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canDelete($movement, $user);
                break;
            case self::MOVEMENT_EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canEdit($movement, $user);
                break;
            case self::MOVEMENT_VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                // On vérifie si on peut voir
                return $this->canView($movement, $user);
                break;
        }

        return false;
    }

    private function canDelete(Movement $movement, User $user)
    {
        // Le propriétaire du mouvement peut l'effacer
        
        return $user === $movement->getUser_Id();
    }

    private function canEdit(Movement $movement, User $user)
    {
        // Le propriétaire du mouvement peut l'editer
        // dd($user->getId());
        // dd($movement);
        // return $user->getId() === $movement->getUser_Id()->getId();
        return $user === $movement->getUser_Id();
    }

    private function canView(Movement $movement, User $user)
    {
        // Le propriétaire des mouvements peut les voir
        
        // dump($user->getId());                        // 2
        // dd($movement->getUser_Id()->getId());        // 1
        
        // dd(AppUser);
        return $user->getId() === $movement->getUser_Id()->getId();
        // return $user === $movement->getUser_Id();
    }

}

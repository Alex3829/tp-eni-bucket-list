<?php

namespace App\Security\Voter;

use App\Entity\Wish;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class WishVoter extends Voter
{
    public const EDIT = 'wish_edit';
    public const DELETE = 'wish_delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Wish;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };
    }

    private function canEdit(Wish $wish, UserInterface $user): bool
    {
        return $user === $wish->getUser() && in_array('ROLE_USER', $user->getRoles());
    }

    private function canDelete(Wish $wish, UserInterface $user): bool
    {
        return ($user === $wish->getUser() && in_array('ROLE_USER', $user->getRoles())) || in_array('ROLE_ADMIN', $user->getRoles());
    }
}

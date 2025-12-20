<?php

namespace App\Security\Voter;

use App\Entity\Advertisement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, Advertisement>
 */
final class AdvertisementVoter extends Voter
{
    public const EDIT = 'ADVERTISEMENT_EDIT';
    public const DELETE = 'ADVERTISEMENT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Advertisement;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $user === $subject->getOwner();
            case self::DELETE:
                return $user === $subject->getOwner();
        }

        return false;
    }
}

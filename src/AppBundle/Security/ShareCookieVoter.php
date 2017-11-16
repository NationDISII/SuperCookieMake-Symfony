<?php
namespace AppBundle\Security;

use AppBundle\Entity\Cookie;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ShareCookieVoter implements VoterInterface {

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @param mixed $subject The subject to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if(!$subject instanceof Cookie) {
            return self::ACCESS_ABSTAIN;
        }
        if(!in_array('SHARE', $attributes)) {
            return self::ACCESS_ABSTAIN;
        }
        $user = $token->getUser();
        if(!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        if($user !== $subject->getOwner()) {
            return self::ACCESS_DENIED;
        }

        return self::ACCESS_GRANTED;
    }
}
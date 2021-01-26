<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('vinnvi');

        $password = $this->encoder->encodePassword($user, 'pass');
        $user->setPassword($password);

        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername('julienik96');

        $user2->setPassword($password);
        $manager->persist($user2);

        $manager->flush();
    }
}

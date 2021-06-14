<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordHasherInterface $encoder)
    {$this->passwordEncoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setTelephone('11111111');
        $user->setPassword($this->passwordEncoder->hashPassword($user,'secret'));
        $manager->persist($user);
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setTelephone('11111111');
        $admin->setPassword($this->passwordEncoder->hashPassword($admin,'secret'));
        $manager->persist($admin);
        $manager->flush();
    }


}

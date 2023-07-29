<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasheur;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasheur = $hasher;
    }
    public function load(ObjectManager $manager): void
    {

        $testUser = new User();
        $testUser->setLastname("user");
        $testUser->setFirstname("user");
        $testUser->setEmail("user@humanbooster.fr");
        $encodedPassword = $this->hasheur->hashPassword($testUser,"user");
        $testUser->setPassword($encodedPassword);
        $testUser->setRoles(["ROLE_USER"]);

        $testRH = new User();
        $testRH->setLastname("rh");
        $testRH->setFirstname("rh");
        $testRH->setEmail("rh@humanbooster.fr");
        $encodedPassword = $this->hasheur->hashPassword($testRH,"rh123");
        $testRH->setPassword($encodedPassword);
        $testRH->setRoles(["ROLE_RH"]);

        $manager->persist($testUser);
        $manager->persist($testRH);

        $manager->flush();
    }
}

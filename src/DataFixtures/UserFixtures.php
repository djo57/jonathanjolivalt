<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("jonathan.jolivalt@gmail.com");
        $user->setPassword('$2y$13$gr5iP5je81niA2brlEWp/e4AhphfX5YhBJC4shx0Y2Hs/oPauSeOS');
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);

        $manager->flush();
    }
}

<?php
# Loading data:
# php bin/console doctrine:fixtures:load --append

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Status;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $Category = new Category();
        $Category->setId(1);
        $Category->setName('Incident');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setId(2);
        $Category->setName('Panne');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setId(3);
        $Category->setName('Evolution');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setId(4);
        $Category->setName('Anomalie');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setId(5);
        $Category->setName('Information');
        $manager->persist($Category);

        $Status = new Status();
        $Status->setId(1);
        $Status->setName('Nouveau');
        $manager->persist($Status);

        $Status = new Status();
        $Status->setId(1);
        $Status->setName('Ouvert');
        $manager->persist($Status);

        $Status = new Status();
        $Status->setId(1);
        $Status->setName('Résolu');
        $manager->persist($Status);

        $Status = new Status();
        $Status->setId(2);
        $Status->setName('Fermé');
        $manager->persist($Status);

        $User = new User();
        $User->setEmail('admin@agence.com');
        $User->setPassword('admin');
        $User->setRoles(['ROLE_ADMIN']);
        $User->setUsername('admin');
        $User->setIsVerified(true);
        $manager->persist($User);


        $manager->flush();
    }
}

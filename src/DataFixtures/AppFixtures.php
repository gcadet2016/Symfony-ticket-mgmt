<?php
# Loading data:
# php bin/console doctrine:fixtures:load --append

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $Category = new Category();
        $Category->setName('Incident');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setName('Panne');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setName('Evolution');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setName('Anomalie');
        $manager->persist($Category);

        $Category = new Category();
        $Category->setName('Information');
        $manager->persist($Category);

        $Status = new Status();
        $Status->setName('Nouveau');
        $manager->persist($Status);

        $Status = new Status();
        $Status->setName('Ouvert');
        $manager->persist($Status);

        $Status = new Status();
         $Status->setName('Résolu');
        $manager->persist($Status);

        $Status = new Status();
        $Status->setName('Fermé');
        $manager->persist($Status);

        $User = new User();
        $User->setEmail('admin@agence.com');
        $User->setPassword($this->hasher->hashPassword($User, 'admin'));
        $User->setRoles(['ROLE_ADMIN']);
        $User->setUsername('admin');
        $User->setIsVerified(true);
        $manager->persist($User);

        $User = new User();
        $User->setEmail('personnel@agence.com');
        $User->setPassword($this->hasher->hashPassword($User, 'perso'));
        $User->setRoles(['ROLE_EDITOR']);
        $User->setUsername('Personnel Agence');
        $User->setIsVerified(true);
        $manager->persist($User);

        $User = new User();
        $User->setEmail('user1@agence.com');
        $User->setPassword($this->hasher->hashPassword($User, 'user1'));
        $User->setRoles(['ROLE_USER']);
        $User->setUsername('User1');
        $User->setIsVerified(true);
        $manager->persist($User);       

        $manager->flush();
    }
}

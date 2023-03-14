<?php

namespace App\Fixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $classicRoom = (new Room())
            ->setTitle('Chambre Classique')
            ->setShortDescription($faker->sentence())
            ->setLongDescription($faker->paragraphs(mt_rand(2, 4), true))
            ->setImage('classic_room.jpg')
            ->setNight(149.99);
        $manager->persist($classicRoom);
        $this->addReference('classic_room', $classicRoom);

        $comfortRoom = (new Room())
            ->setTitle('Chambre Confort')
            ->setShortDescription($faker->sentence())
            ->setLongDescription($faker->paragraphs(mt_rand(2, 4), true))
            ->setImage('comfort_room.jpg')
            ->setNight(279.99);
        $manager->persist($comfortRoom);
        $this->addReference('comfort_room', $comfortRoom);

        $suiteRoom = (new Room())
            ->setTitle('Chambre Suite')
            ->setShortDescription($faker->sentence())
            ->setLongDescription($faker->paragraphs(mt_rand(2, 4), true))
            ->setImage('suite_room.jpg')
            ->setNight(279.99);
        $manager->persist($suiteRoom);
        $this->addReference('suite_room', $suiteRoom);

        $manager->flush();
    }
}

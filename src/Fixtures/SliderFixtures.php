<?php

namespace App\Fixtures;

use App\Entity\Slider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SliderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $slider = (new Slider())
                ->setPlace($i)
                ->setImage("slider-$i.jpg");

            $manager->persist($slider);
        }
        $manager->flush();
    }
}

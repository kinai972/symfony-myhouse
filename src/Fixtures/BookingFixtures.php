<?php

namespace App\Fixtures;

use App\Entity\Room;
use App\Entity\Booking;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            /** @var Room */
            $room = $this->getReference(['classic', 'comfort', 'suite'][mt_rand(0, 2)] . '_room');

            $booking = (new Booking())
                ->setRoom($room)
                ->setRoomReference($room->getId() . ' - ' . $room->getTitle())
                ->setStartsAt(DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween(startDate: '-2 weeks', endDate: '-2 days')
                ))
                ->setEndsAt(DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween(startDate: '+2 days', endDate: '+2 weeks')
                ))
                ->setEmail($faker->email())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPhoneNumber($faker->phoneNumber());

            $bookingDuration =
                $booking
                ->getStartsAt()
                ->diff($booking->getEndsAt())
                ->days;

            $booking->setTotal($bookingDuration * $room->getNight());

            $manager->persist($booking);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [RoomFixtures::class];
    }
}

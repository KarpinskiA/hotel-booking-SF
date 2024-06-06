<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use App\Entity\User;
use App\Entity\Hotel;
use DateTimeImmutable;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
        //
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $faker->seed(2024);

        // Création d'un utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, '0000'))
            ->setLastname('Admin')
            ->setFirstname('Admin');
        $manager->persist($admin);

        // Création de 10 propriétaires
        for ($i = 1; $i <= 10; $i++) {
            $owner = new User();
            $owner->setEmail("owner{$i}@owner.fr")
                ->setRoles(['ROLE_OWNER'])
                ->setPassword($this->hasher->hashPassword($owner, '0000'))
                ->setLastname($faker->lastName())
                ->setFirstname($faker->firstName());
            $manager->persist($owner);
            $this->addReference('owner_' . $i, $owner);
        }

        // Création de 10 utilisateurs
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@user.fr")
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->hasher->hashPassword($user, '0000'))
                ->setLastname($faker->lastName())
                ->setFirstname($faker->firstName());
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        // Création de 10 hôtels
        for ($i = 1; $i <= 10; $i++) {
            $hotel = new Hotel();
            $hotel->setName($faker->company())
                ->setAddress($faker->streetAddress())
                ->setCity($faker->city())
                ->setPostalCode($faker->postcode())
                ->setCountry('France')
                ->setPhone($faker->serviceNumber())
                ->setOwner($this->getReference('owner_' . $i));
            $manager->persist($hotel);
            $this->addReference('hotel_' . $i, $hotel);
        }

        // création de 50 chambres
        for ($i = 1; $i <= 50; $i++) {
            $room = new Room();
            $room->setName($faker->word())
                ->setDescription($faker->sentence())
                ->setPrice($faker->randomFloat(2, 50, 200))
                ->setIsRoom($this->getReference('hotel_' . $faker->numberBetween(1, 10)));
            $manager->persist($room);
            $this->addReference('room_' . $i, $room);
        }

        // création de 100 réservations
        for ($i = 1; $i <= 100; $i++) {
            $reservation = new Reservation();

            // Génération de la date de checkIn (entre maintenant et dans 6 mois)
            $randomCheckIn = $faker->dateTimeBetween('now', '+6 months');

            // Génération de la date de checkOut (entre le checkIn et jusqu'à 2 mois après)
            $randomCheckOut = $faker->dateTimeBetween($randomCheckIn, (clone $randomCheckIn)->modify('+2 months'));

            // On formate les dates pour ne garder que la date sans l'heure (format YYYY-MM-DD)
            $randomCheckInFormated = new DateTimeImmutable($randomCheckIn->format('Y-m-d'));
            $randomCheckOutFormated = new DateTimeImmutable($randomCheckOut->format('Y-m-d'));

            // Calcul du nombre de jours entre les deux dates (checkIn et checkOut)
            $totalDays = $randomCheckInFormated->diff($randomCheckOutFormated)->days;

            // Si la durée du séjour est supérieure à un jour,
            // on soustrait un jour pour ne pas compter le jour de départ
            if ($totalDays > 1) {
                $totalDays--;
            }

            $reservation->setCheckIn($randomCheckInFormated)
                ->setCheckOut($randomCheckOutFormated)
                ->setTotalDays($totalDays)
                ->setIsAbout($this->getReference('room_' . $faker->numberBetween(1, 50)))
                ->setReservedBy($this->getReference('user_' . $faker->numberBetween(1, 10)));
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}

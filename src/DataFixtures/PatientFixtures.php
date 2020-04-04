<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Patient;
use App\Entity\Consultation;
use Faker;

class PatientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 30; $i++){

            $patient=new Patient();
            $patient->setPrenom($faker->firstName)
                    ->setNom($faker->lastName)
                    ->setCin($faker->swiftBicNumber)
                    ->setDateNaissance($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null))
                    ->setGenre("musculin $i")
                    ->setNumTel($faker->phoneNumber)
                    ->setCreatedAt($faker->DateTime($max = 'now', $timezone = null));
            
            $manager->persist($patient);
        }

        // for($j = 1; $j <= mt_rand(56,66); )
        // {
        //     $consultation = new Consultation();
        //     $consultation->setPatients($patient)
        //                  ->setDateRdv($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null))
        //                  ->setDescription($faker->sentence())
        //                  ->setRealisation("oui")
        //                  ->setCreatedAt($faker->DateTime());
                         
        //     $manager->persist($consultation);

        // }


        $manager->flush();
    }
}

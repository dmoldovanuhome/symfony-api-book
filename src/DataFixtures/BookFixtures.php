<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BookFixtures extends Fixture
{

    private static $bookTitles = [
        'Why Asteroids Taste Like Bacon',
        'Life on Planet Mercury: Tan, Relaxing and Fabulous',
        'Light Speed Travel: Fountain of Youth or Fallacy',
        'Super Hero Academy',
        'Alternative history',
        'Vision of Wealthy',
        'Health care',
        'British encyclopedia',
    ];

    private static $bookAuthorFirstnames = [
        'Mike',
        'Amy',
        'Jonny',
        'Ivan',
        'Vasiliy',
        'Fiodor',
        'Sam',
        'Cristian',
        'Nail',
        'Roy',
        'Nikita',
    ];

    private static $bookAuthorLastnames = [
        'Ferengi',
        'Oort',
        'Mercury',
        'Blackberry',
        'Dostoevsky',
        'Stary',
        'Lebeda',
        'Star',
        'Ivashov',
        'Kant',
        'Kert',
        'Andresen',
        'Pierreau',
    ];

    /** @var Generator */
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        // create 1 000 000 records
        $chunkSize = 20;
        for ($i = 1; $i <= 1000000; $i++) {
            $book = new Book();
            $book->setTitle($this->faker->randomElement(self::$bookTitles) . ' vol. ' . $i);
            $book->setAuthor(
                $this->faker->randomElement(self::$bookAuthorFirstnames) . ' ' .
                $this->faker->randomElement(self::$bookAuthorLastnames)
            );
            $book->setDescription($this->faker->paragraph());
            $book->setPrice($this->faker->randomFloat(2, 1, 500));

            $manager->persist($book);

            if ($i % $chunkSize === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
        $manager->clear();
    }
}

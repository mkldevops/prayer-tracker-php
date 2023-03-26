<?php

namespace App\DataFixtures;

use App\Entity\PrayerName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PrayerNameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::provider() as $item) {
            $prayer = (new PrayerName())->setName($item['name'])->setDescription($item['description']);
            $manager->persist($prayer);
        }

        $manager->flush();
    }

    private static function provider(): array
    {
        return [
            ['name' => 'Adh–dhouhr', 'description' => 'C’est la prière que l’on accomplit à la mi-journée. Elle est composée de quatre rak^ah (unités de prière).'],
            ['name' => 'Al-^asr', 'description' => 'C’est la prière que l’on accomplit dans l’après-midi. Elle est composée de quatre rak^ah (unités de prière).'],
            ['name' => 'Al-maghrib', 'description' => 'C’est la prière que l’on accomplit après le coucher du soleil. Elle est composée de trois rak^ah (unités de prière).'],
            ['name' => 'Al-^icha‘', 'description' => 'C’est la prière que l’on accomplit la nuit. Elle est composée de quatre rak^ah.'],
            ['name' => 'As-Soubh', 'description' => 'Elle est appelée également al-Fajr, c’est la prière que l’on accomplit à l’aube. Elle est composée de deux rak^ah.'],
        ];
    }
}

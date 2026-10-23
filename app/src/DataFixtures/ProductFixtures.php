<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Informatique');
        $category->setType(1);
        $category->setParent(null);
        $category->setSlug('informatique');
        $category->setUrl('informatique.webp');
        $category->setAlt('Informatique');
        $manager->persist($category);
        $array_informatique = ['Ordinateur portable', 'Ordinateur de bureau', 'Pièces détachées', 'Périphériques'];
        foreach ($array_informatique as $name) {
            $subCategory = new Category();
            $subCategory->setName($name);
            $subCategory->setType(1);
            $subCategory->setParent($category);
            $subCategory->setSlug(strtolower(str_replace(' ', '-', $name)));
            $subCategory->setUrl(strtolower(str_replace(' ', '-', $name)) . '.webp');
            $subCategory->setAlt($name);
            $manager->persist($subCategory);
        }
        $telephonie = new Category();
        $telephonie->setName('Téléphonie');
        $telephonie->setType(2);
        $telephonie->setParent(null);
        $telephonie->setSlug('telephonie');
        $telephonie->setUrl('telephonie.webp');
        $telephonie->setAlt('Téléphonie');
        $manager->persist($telephonie);
        $array_telephonie = ['Smartphone', 'Fixe', 'auto'];
        foreach ($array_telephonie as $name) {
            $subCategory = new Category();
            $subCategory->setName($name);
            $subCategory->setType(2);
            $subCategory->setParent($telephonie);
            $subCategory->setSlug(strtolower(str_replace(' ', '-', $name)));
            $subCategory->setUrl(strtolower(str_replace(' ', '-', $name)) . '.webp');
            $subCategory->setAlt($name);
            $manager->persist($subCategory);
        }
        $image = new Category();
        $image->setName('Image');
        $image->setType(3);
        $image->setParent(null);
        $image->setSlug('image');
        $image->setUrl('image.webp');
        $image->setAlt('Image');
        $manager->persist($image);
        $array_image = ['Appareil photo', 'Caméra', 'Télévision'];
        foreach ($array_image as $name) {
            $subCategory = new Category();
            $subCategory->setName($name);
            $subCategory->setType(3);
            $subCategory->setParent($image);
            $subCategory->setSlug(strtolower(str_replace(' ', '-', $name)));
            $subCategory->setUrl(strtolower(str_replace(' ', '-', $name)) . '.webp');
            $subCategory->setAlt($name);
            $manager->persist($subCategory);
        }
        $manager->flush();
    }
}

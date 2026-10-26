<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    public function  __construct(private readonly SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        /* informatique */
        $informatique = new Category();
        $informatique->setName('Informatique');
        $informatique->setType(1);
        $informatique->setSubType(null);
        $informatique->setParent(null);
        $informatique->setSlug('informatique');
        $informatique->setUrl('informatique.webp');
        $informatique->setAlt('Informatique');
        $manager->persist($informatique);

        $array_informatique = ['Ordinateur portable', 'Ordinateur de bureau', 'Pièces détachées', 'Périphériques'];
        $array_ordinateur_portable = ['PC portable','MacBook','PC reconditionné','MacBook reconditionné'];
        $array_ordinateur_bureau = ['PC bureau','Ordinateur Mac','PC reconditionné','Mac reconditionné'];
        $array_pieces_detachees = ['carte mère','carte graphique','processeur','mémoire'];
        $array_peripheriques = ['Ecran','clavier souris','imprimante','webcam'];

        $count = 1;
        foreach ($array_informatique as $name) {
            $subCategory = new Category();
            $subCategory->setName($name);
            $subCategory->setType(1);
            $subCategory->setSubType($count);
            if($subCategory->getSubType() === 1){
            foreach ($array_ordinateur_portable as $genre)
            {
                $typeProduit = new Category();
                $typeProduit->setName($genre);
                $typeProduit->setType(1);
                $typeProduit->setSubType(null);
                $typeProduit->setParent($subCategory);
                $typeProduit->setSlug(strtolower(str_replace(' ','-',$genre)));
                $typeProduit->setUrl(strtolower(str_replace(' ','-',$genre)). '.webp');
                $typeProduit->setAlt($genre);
                $manager->persist($typeProduit);
                $pcPortables[] = $typeProduit;

            }
            }
            if($subCategory->getSubType() === 2){
                foreach ($array_ordinateur_bureau as $genre)
                {
                    $typeProduit = new Category();
                    $typeProduit->setName($genre);
                    $typeProduit->setType(1);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower((str_replace(' ','-',$genre))));
                    $typeProduit->setUrl(strtolower(str_replace(' ','-',$genre)). '.webp');
                    $typeProduit->setAlt($genre);
                    $manager->persist($typeProduit);
                    $pcBureau[] = $typeProduit;
                }
            }

            if($subCategory->getSubType() === 3){
                foreach ($array_pieces_detachees as $genre)
                {
                    $typeProduit = new Category();
                    $typeProduit->setName($genre);
                    $typeProduit->setType(1);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower((str_replace(' ','-',$genre))));
                    $typeProduit->setUrl(strtolower(str_replace(' ','-',$genre)). '.webp');
                    $typeProduit->setAlt($genre);
                    $manager->persist($typeProduit);
                }
            }
            if($subCategory->getSubType() === 4){
                foreach ($array_peripheriques as $genre)
                {
                    $typeProduit = new Category();
                    $typeProduit->setName($genre);
                    $typeProduit->setType(1);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower((str_replace(' ','-',$genre))));
                    $typeProduit->setUrl(strtolower(str_replace(' ','-',$genre)). '.webp');
                    $typeProduit->setAlt($genre);
                    $manager->persist($typeProduit);
                }
            }
            $subCategory->setParent($informatique);
            $subCategory->setSlug(strtolower(str_replace(' ', '-', $name)));
            $subCategory->setUrl($subCategory->getSlug() . '.webp');
            $subCategory->setAlt($name);
            $manager->persist($subCategory);
            $count++;

        }

        /* telephonie */
        $telephonie = new Category();
        $telephonie->setName('Téléphonie');
        $telephonie->setType(2);
        $telephonie->setSubType(null);
        $telephonie->setParent(null);
        $telephonie->setSlug('telephonie');
        $telephonie->setUrl('telephonie.webp');
        $telephonie->setAlt('Téléphonie');
        $manager->persist($telephonie);

        $array_telephonie = ['Smartphone', 'Fixe', 'auto'];
        $array_smartphone = ['mobile android','iphone','android reconditionné','iphone reconditionné'];
        $array_tel_fixe = ['téléphone sans fil','téléphone Voip','téléphone filaire'];
        $array_auto =['kit main libre','dashcam','autoradio'];

        $count = 1;
        foreach ($array_telephonie as $name) {
            $subCategory = new Category();
            $subCategory->setName($name);
            $subCategory->setType(2);
            $subCategory->setSubType($count);
            if($subCategory->getSubType()===1){
                foreach ($array_smartphone as $smartphone)
                {
                    $typeProduit = new Category();
                    $typeProduit->setName($smartphone);
                    $typeProduit->setType(2);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower(str_replace(' ','-',$smartphone)));
                    $typeProduit->setUrl($typeProduit->getSlug() . '.webp');
                    $typeProduit->setAlt($smartphone);
                    $manager->persist($typeProduit);
                }
            }
            if($subCategory->getSubType()===2){
                foreach ($array_tel_fixe as $smartphone)
                {
                    $typeProduit = new Category();
                    $typeProduit->setName($smartphone);
                    $typeProduit->setType(2);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower(str_replace(' ','-',$smartphone)));
                    $typeProduit->setUrl($typeProduit->getSlug() . '.webp');
                    $typeProduit->setAlt($smartphone);
                    $manager->persist($typeProduit);
                }
            }
            if($subCategory->getSubType()===3){
                foreach ($array_auto as $smartphone)
                {
                    $typeProduit = new Category();
                    $typeProduit->setName($smartphone);
                    $typeProduit->setType(2);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower(str_replace(' ','-',$smartphone)));
                    $typeProduit->setUrl($typeProduit->getSlug() . '.webp');
                    $typeProduit->setAlt($smartphone);
                    $manager->persist($typeProduit);
                }
            }
            $subCategory->setParent($telephonie);
            $subCategory->setSlug(strtolower(str_replace(' ', '-', $name)));
            $subCategory->setUrl(strtolower(str_replace(' ', '-', $name)) . '.webp');
            $subCategory->setAlt($name);
            $manager->persist($subCategory);
            $count++;
        }

/* image */
        $image = new Category();
        $image->setName('Image');
        $image->setType(3);
        $image->setParent(null);
        $image->setSlug('image');
        $image->setUrl('image.webp');
        $image->setAlt('Image');
        $manager->persist($image);
        $array_image = ['Appareil photo', 'Caméra', 'Télévision'];
        $array_photo = ['photo numérique','photo reflex','photo hybride'];
        $array_camera = ['caméscope','micro','carte mémoire'];
        $array_television = ['television','antenne','adaptateur tnt'];

        $count = 1;
        foreach ($array_image as $name) {
            $subCategory = new Category();
            $subCategory->setName($name);
            $subCategory->setType(3);
            $subCategory->setSubType($count);
            if($subCategory->getSubType() ===1)
            {
                foreach ($array_photo as $photo){
                    $typeProduit = new Category();
                    $typeProduit->setName($photo);
                    $typeProduit->setType(3);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower(str_replace(' ','-',$photo)));
                    $typeProduit->setUrl($typeProduit->getSlug() . '.webp');
                    $typeProduit->setAlt($photo);
                    $manager->persist($typeProduit);
                }
            }
            if($subCategory->getSubType() ===2)
            {
                foreach ($array_camera as $photo){
                    $typeProduit = new Category();
                    $typeProduit->setName($photo);
                    $typeProduit->setType(3);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower(str_replace(' ','-',$photo)));
                    $typeProduit->setUrl($typeProduit->getSlug() . '.webp');
                    $typeProduit->setAlt($photo);
                    $manager->persist($typeProduit);
                }
            }
            if($subCategory->getSubType() ===3)
            {
                foreach ($array_television as $photo){
                    $typeProduit = new Category();
                    $typeProduit->setName($photo);
                    $typeProduit->setType(3);
                    $typeProduit->setSubType(null);
                    $typeProduit->setParent($subCategory);
                    $typeProduit->setSlug(strtolower(str_replace(' ','-',$photo)));
                    $typeProduit->setUrl($typeProduit->getSlug() . '.webp');
                    $typeProduit->setAlt($photo);
                    $manager->persist($typeProduit);
                }
            }
            $subCategory->setParent($image);
            $subCategory->setSlug(strtolower(str_replace(' ', '-', $name)));
            $subCategory->setUrl(strtolower(str_replace(' ', '-', $name)) . '.webp');
            $subCategory->setAlt($name);
            $manager->persist($subCategory);
            $count++;
        }

        $array_marks = ['Toshiba','MSI','Acer','Lenovo','Asus','Apple','Gigabyte'];
        $array_netbooks = ['portable-1.webp','portable-2.webp','portable-3.webp'];
        $type_produits = ['Intel Core i3-1220P 16 Go SSD 480 Go 16" LED Full HD+ Wi-Fi 6E Webcam Windows 11 Famille',
        'Intel Core i5-1240P 16 Go SSD 960 Go 16" LED Full HD+ Wi-Fi 6E Webcam Windows 11 Famille',
            'Intel Core i9-13900H 32 Go SSD 1 To 16" LED Full HD+ Wi-Fi 6/Bluetooth Webcam Windows 11 Famille',
            'AMD Ryzen 7 350 32 Go SSD 1 To 18" LED QHD+ 165 Hz NVIDIA GeForce RTX 5070 8 Go DLSS 4 Wi-Fi 6E/Bluetooth Webcam Windows 11 Famille'];
        for($i = 0; $i < 50; $i++ ) {
                $produit = new Product();
                $produit->setName($array_marks[random_int(0,count($array_marks)-1)]);
                $produit->setSlug(strtolower($this->slugger->slug($produit->getName())));
                $produit->setDescription(array_rand($type_produits));
                $produit->setPrice(mt_rand(1000, 3000));
                $produit->setStock(mt_rand(10, 20));
                $produit->setCreatedAt(new \DateTimeImmutable());
                $produit->setCategorie($pcPortables[random_int(0, count($pcPortables) - 1)]);
                foreach ($array_netbooks as $netbook) {
                    $image = new Image();
                    $image->setName($netbook);
                    $image->setProduct($produit);
                    $produit->addImage($image);
                    $manager->persist($image);
                }
                $manager->persist($produit);
            }
        $array_netbooks = ['ordinateur-1.webp','ordinateur-2.webp','ordinateur-3.webp','ordinateur-4.webp'];
        for($i = 0; $i < 50; $i++ ) {

                $produit = new Product();
                $produit->setName($array_marks[random_int(0,count($array_marks)-1)]);
                $produit->setSlug(strtolower($this->slugger->slug($produit->getName())));
                $produit->setDescription(array_rand($type_produits));
                $produit->setPrice(mt_rand(1000, 3000));
                $produit->setStock(mt_rand(10, 20));
                $produit->setCreatedAt(new \DateTimeImmutable());
                $produit->setCategorie($pcBureau[random_int(0, count($pcBureau) - 1)]);
                foreach ($array_netbooks as $netbook) {
                    $image = new Image();
                    $image->setName($netbook);
                    $image->setProduct($produit);
                    $produit->addImage($image);
                    $manager->persist($image);
                }
                $manager->persist($produit);

        }
        $manager->flush();


    }
}

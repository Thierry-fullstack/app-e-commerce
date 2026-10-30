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

        $array_informatique = ['Ordinateur portable', 'Ordinateur de bureau', 'Pièces détachées', 'Périphériques'];
        $array_ordinateur_portable = ['Portable Windows','MacBook','Portable reconditionné','MacBook reconditionné'];
        $array_ordinateur_bureau = ['PC bureau','Ordinateur Mac','PC reconditionné','Mac reconditionné'];
        $array_pieces_detachees = ['carte mère','carte graphique','processeur','mémoire'];
        $array_peripheriques = ['Ecran','clavier souris','imprimante','webcam'];

        $array_telephonie = ['Smartphone', 'Fixe', 'auto'];
        $array_smartphone = ['mobile android','iphone','android reconditionné','iphone reconditionné'];
        $array_tel_fixe = ['téléphone sans fil','téléphone Voip','téléphone filaire'];
        $array_auto =['kit main libre','dashcam','autoradio'];

        $array_image = ['Appareil photo', 'Caméra', 'Télévision'];
        $array_photo = ['photo numérique','photo reflex','photo hybride'];
        $array_camera = ['caméscope','micro','carte mémoire'];
        $array_television = ['television','antenne','adaptateur tnt'];

        $array_marks = ['Toshiba','MSI','Acer','Lenovo','Asus','Apple','Gigabyte'];
        $array_netbooks = ['portable-1.webp','portable-2.webp','portable-3.webp'];
        $type_produits = [
            'Intel Core i3-1220P 16 Go SSD 480 Go 16" LED Full HD+ Wi-Fi 6E Webcam Windows 11 Famille',
            'Intel Core i5-1240P 16 Go SSD 960 Go 16" LED Full HD+ Wi-Fi 6E Webcam Windows 11 Famille',
            'Intel Core i9-13900H 32 Go SSD 1 To 16" LED Full HD+ Wi-Fi 6/Bluetooth Webcam Windows 11 Famille',
            'AMD Ryzen 7 350 32 Go SSD 1 To 18" LED QHD+ 165 Hz NVIDIA GeForce RTX 5070 8 Go DLSS 4 Wi-Fi 6E/Bluetooth Webcam Windows 11 Famille'];
        $array_netbooks = ['ordinateur-1.webp','ordinateur-2.webp','ordinateur-3.webp','ordinateur-4.webp'];



    }
}

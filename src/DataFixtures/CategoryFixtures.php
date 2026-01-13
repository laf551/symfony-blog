<?php

namespace App\DataFixtures;
//bundle == Package de fichier 
use App\Entity\Category; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $catégories =['Motivation ','Encouragement', 'Prière', 'Developpement personnel'];
        foreach ( $catégories as $categoryName){
            $category = new Category(); 
            $category->setName($categoryName); 
            $manager->persist($category); 
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
     
?>
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Category; 

use Doctrine\ORM\EntityManagerInterface ; //Comm entre php et bd : CRUD, transactions, mapping 


final class CategoryController extends AbstractController
{
    #[Route('/category',name:'category_list')]
    public function list(EntityManagerInterface $em) : Response{
        //entité Category
        $categories = $em->getRepository(Category::class)->findAll(); 

        return $this->render('category/list.html.twig',['category' => $categories,]); 

    } 

    #[Route('/category/new', name:'category_new')]
    public function new(Request $request, EntityManagerInterface $em): Response{
        $category = new Category(); 
        $form = $this->createForm(\App\Form\CategoryType::class, $category); 
        $form->handleRequest($request);

        if($form-> isSubmitted() && $form->isValid()){
            $em->persist($category); #modif dans la base 
            $em->flush() ; #ajout dans la base 

            return $this->redirectToRoute('app_category'); 
        }
        return $this->render('category/new.html.twig',['form' => $form,]); 
    }
    


    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}

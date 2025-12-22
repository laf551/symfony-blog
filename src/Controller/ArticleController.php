<?php 
namespace App\Controller;

use App\Entity\Article; 
use Doctrine\ORM\EntityManagerInterface; #db
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
#AbstractController : utiliser la boîte à outils de Symfony”.

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ArticleController extends AbstractController{ #héritage 

    #[Route('/articles',name:'article_list')]
    public function list(EntityManagerInterface $em):Response{
        $articles = $em->getRepository(Article::class)->findAll(); #getAllArticle de db
        return $this->render('article/list.html.twig', ['articles'=>$articles,]);#envoi au template affichage
        #render: montre la page
        #articles valeur retourné qu'on utilisera dans la boucle
    }

    // IMPORTANT : cette route DOIT être AVANT article/{id}
    #[Route('/articles/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setPublishedAt(new \DateTime());
        
        $form = $this->createForm(\App\Form\ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute('article_list');
        }
        
        return $this->render('article/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/articles/{id}' , name:'article_show')]
    public function show(Article $article):Response{
        return $this->render('article/show.html.twig',['article' => $article,]); #template
    }
}

?>
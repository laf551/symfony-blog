<?php 
namespace App\Controller;

use App\Entity\Article; 
use Doctrine\ORM\EntityManagerInterface; #db
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
#AbstractController : utiliser la boîte à outils de Symfony”.

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Notifier\Event\MessageEvent; 


class ArticleController extends AbstractController{ #héritage 
    
    
    #[Route('/articles',name:'article_list')]
    public function list(EntityManagerInterface $em):Response{
        $articles = $em->getRepository(Article::class)->findAll(); #getAllArticle de db
        return $this->render('article/list.html.twig', ['articles'=>$articles,]);#envoi au template affichage
        #render: montre la page
        #articles valeur retourné qu'on utilisera dans la boucle
    }

    // IMPORTANT : cette route DOIT être AVANT article/{id}
    //CREATE 
    #[Route('/articles/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setPublishedAt(new \DateTime());
        
        $form = $this->createForm(\App\Form\ArticleType::class, $article);
        $form->handleRequest($request);
        
        //type="submit"
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute('article_list');
        }
        
        return $this->render('article/new.html.twig', [
            'form' => $form,
        ]);
    }

    //READ
    #[Route('/articles/{id}' , name:'article_show')]
    public function show(Article $article):Response{
        return $this->render('article/show.html.twig',['article' => $article,]); #template
    }

    //------------------------------------------------------------------------

    //UPDATE
    #[Route('/articles/{id}/edit' , name:'article_edit')]
    public function edit(Request $request, EntityManagerInterface $em, Article $article): Response{
        
        $form = $this->createForm(\App\Form\ArticleType::class,$article); 
        /**  Symfony lie automatiquement le formulaire à l'objet $article.
         *  Le champ title affiche déjà $article->getTitle() 
         *  Le champ content affiche déjà $article->getContent() 
        **/
        $form->handleRequest($request); 

        if($form->isSubmitted () && $form->isValid()){
            //Pas besoin de persist() car l'article existe déjà en base
            $em-> flush();  //écrit dans la base
            return $this->redirectToRoute('article_list'); 
        }
        return $this->render('article/edit.html.twig',['form' => $form,'article' =>$article,]); 
        //un seul tableau []
        //le form remplit auto les valeurs depuis $article

    }

    //DELETE 
    #[Route('/articles/{id}/delete' , name:'article_delete',methods:['POST'])]
    public function delete(Request $request , EntityManagerInterface $em, Article $article): Response{
        
        $em->remove($article);
        $em-> flush();
        //return $this->render('article/list.html.twig',['articles' => $article,]); 
        return $this->redirectToRoute('article_list'); 

    }
    
}

?>
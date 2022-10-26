<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\BookService;
use App\Repository\BookRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;

class MainController extends AbstractController
{
    
    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    
    /**
     * @Route("/dummy", name="app_dummy")
     */
    public function dummy(): Response
    { 
        return $this->render('main/dummy.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    
    /**
     * @Route("/dummyadd", name="app_dummyadd")
     */
    public function dummyadd() //: Response
    { 

        BookService::addDummyBooks($this->getDoctrine()->getManager());
            
        return $this->redirectToRoute('app_book', [], 301);
    }
    
}

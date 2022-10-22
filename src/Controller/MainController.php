<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\AuthorService;
use App\Services\BookService;

use Doctrine\ORM\EntityManagerInterface;


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
    public function dummyadd(): Response
    {
        //AuthorService::addDummyAuthors($this->getDoctrine()->getManager());
        BookService::addDummyBooks($this->getDoctrine()->getManager());
        
        //TODO: redirect to main page after full dummy fuction will complited
        //return $this->redirectToRoute('app_main', [], 301);
        
        //return $this->redirectToRoute('app_author', [], 301);
        
        return $this->redirectToRoute('app_book', [], 301);
    }
}

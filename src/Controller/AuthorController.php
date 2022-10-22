<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

use \App\Form\AuthorFormType;
use \App\Entity\Author;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="app_author")
     */
    public function index(Request $request): Response
    {
        $searchfor = $request->query->get('searchfor');
        if ($searchfor) {
            $authors = $this->getDoctrine()->getRepository(Author::class)->filterByName($searchfor);
        }
        else {
            $authors = $this->getDoctrine()->getRepository(Author::class)->findAll();
        }
        
        return $this->render('author/author.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $authors,
            'searchfor' => $searchfor,
        ]);
    }
    
    /**
     * @Route("/author/show/{id}", name="app_authorshow")
     */
    public function showAuthor($id, Environment $twig, Request $request): Response
    {
        if ($id == 0) {
            $author = new Author();        
        }
        else {
            $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        }
        $form = $this->createForm(AuthorFormType::class, $author);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($id == 0) {
                $author->setBooksCount(0);
                $this->getDoctrine()->getRepository(Author::class)->add($author);
            }
            else {
                $this->getDoctrine()->getRepository(Author::class)->save($author);
            }
            
            return $this->redirectToRoute('app_author', [], 301);
        }        
        return new Response($twig->render("author/show.html.twig", [
            "author_form" => $form->createView()
        ]));
    }

    /**
     * @Route("/author/delete/{id}", name="app_authordelete")
     */
    public function deleteAuthor($id): Response
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        
        if($author) {
            $this->getDoctrine()->getRepository(Author::class)->remove($author);
        }
        
        return $this->redirectToRoute('app_author', [], 301);
    }

    
}

<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\FileUploader;
use App\Form\BookFormType;
use App\Entity\Book;

class BookController extends AbstractController
{


    /**
     * @Route("/book", name="app_book")
     */
    public function index(Request $request): Response
    {

        //check if we've got data from inline save
        $saveFromInline = $request->request->get('book_flag_inline');
        if ($saveFromInline) {
            $book = $this->getDoctrine()->getRepository(Book::class)->find($saveFromInline);
            $book->setTitle($request->request->get("book_title_" . $saveFromInline));
            $book->setDescription($request->request->get("book_description_" . $saveFromInline));
            $book->setYear($request->request->get("book_year_" . $saveFromInline));
            //$this->getDoctrine()->getRepository(Book::class)->save($book);


            $this->getDoctrine()->getManager()->flush();
        }

        $searchfor = $request->query->get('searchfor');

        if ($searchfor) {
            $books = $this->getDoctrine()->getRepository(Book::class)->filterByFields($searchfor);
        } else {
            $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
        }

        return $this->render('book/book.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
            'searchfor' => $searchfor,
            'saveFromInline' => $saveFromInline,
        ]);
    }

    /**
     * @Route("/book/edit/{id}", name="app_bookedit")
     */
    public function editBook($id, Request $request): Response
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $book->setCoverFilename($this->uploadAndGetCoverFileName($form->get('cover')->getData()));

            $this->getDoctrine()->getRepository(Book::class)->save($book, true, $this->getDoctrine()->getManager());

            return $this->redirectToRoute('app_book', [], 301);
        }

        return $this->render('book/detail.html.twig', [
            "book_form" => $form->createView()
        ]);

    }

    /**
     * @Route("/book/create", name="app_bookcreate")
     */
    public function createBook(Request $request): Response
    {
        $book = new Book();
        $book->setYear(date("Y"));

        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $book->setCoverFilename($this->uploadAndGetCoverFileName($form->get('cover')->getData()));

            $this->getDoctrine()->getRepository(Book::class)->add($book);
            return $this->redirectToRoute('app_book', [], 301);
        }

        return $this->render('book/detail.html.twig', [
            "book_form" => $form->createView()
        ]);

    }

    private function uploadAndGetCoverFileName($coverFile) : String
    {
        if ($coverFile) {
            $fileUploader = new FileUploader($this->getParameter('cover_directory'));
            $coverFileName = $fileUploader->upload($coverFile);
            return $coverFileName;
        }

        return "";
    }


    /**
     * @Route("/book/delete/{id}", name="app_bookdelete")
     */
    public function deleteBook($id): Response
    {

        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        if ($book) {
            try {
                if ($book->getCoverFilename()) {
                    $file = $this->getParameter('cover_directory') . "\\" . $book->getCoverFilename();
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                $this->getDoctrine()->getRepository(Book::class)->remove($book);

            } catch (Exception $ex) {
                return $this->render('main/error.html.twig', [
                    "exception" => $ex
                ]);
            }
        }
        return $this->redirectToRoute('app_book', [], 301);
    }

}

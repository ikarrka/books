<?php


namespace App\Controller;


use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiBookController extends AbstractController
{

    /**
     * @Route("/api/book/update/{id}", name="book_update", methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id): Response
    {

        $parameters = json_decode($request->getContent(), true);

        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $date = new \DateTime();
        $moment = $date->format('Y-m-d H:i:s');
        $book
            ->setTitle($parameters["title"])
            ->setDescription($parameters["description"])
            ->setYear($parameters["year"]);

        $this->getDoctrine()->getRepository(Book::class)->save($book);

        return $this->json("Updated successfull");
    }

    /**
     * @Route("/api/book/delete/{id}", name="book_delete", methods={"DELETE"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, $id): Response
    {

        $parameters = json_decode($request->getContent(), true);

        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        $this->getDoctrine()->getRepository(Book::class)->remove($book);

        return $this->json("Updated successfull");
    }
}
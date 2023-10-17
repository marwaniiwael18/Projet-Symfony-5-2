<?php

namespace App\Controller;

use App\Entity\Book; // Import the Book entity
use App\Form\FormBookType;
use Doctrine\ORM\EntityManagerInterface; // Use EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addbook', name: 'app_book_add')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(FormBookType::class, $book); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book); // Persist the book object
            $entityManager->flush(); // Save it to the database

            // Optionally, redirect to another route after saving
            // return $this->redirectToRoute('some_route_name');
        }

        return $this->render('book/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
}

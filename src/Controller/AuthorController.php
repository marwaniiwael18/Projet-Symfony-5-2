<?php

namespace App\Controller;
use App\Form\FormAuthorType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
        'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
        ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
        'taha.hussein@gmail.com', 'nb_books' => 300),
        );

   

    #[Route('/authorshow/{name}', name: 'author_show')]
    public function show($name): Response
    {
        return $this->render('author/show.html.twig' , ['n' =>$name ]);
    }

    #[Route('/authorlist', name: 'show_list')]
    public function list(): Response 
    {
        return $this->render('author/list.html.twig', [
            'authors' => $this->authors
        ]);  
    }

    // Rename this to "authordetails" to match the route name in the twig template
    #[Route('/authordetails/{id}', name: 'authordetails')]
    public function authorDetails($id): Response 
    {
        $author = array_filter($this->authors, function($a) use ($id) {
            return $a['id'] == $id;
        });

        $author = reset($author); // Get the first matched author

        if (!$author) {
            throw $this->createNotFoundException('No author found for id '.$id);
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author
        ]);   
    }
    #[Route('/author', name: 'app_author')]
    public function index(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'controller_name' => 'AuthorController', 
        ]);
    }
    
    #[Route('/author/add', name: 'app_author_add')]
    public function addAuthor(ManagerRegistry $manager){
        $author = new Author();
        $author->setUsername('author2');
        $author->setEmail('author2@esprit.tn');
        $manager->getManager()->persist($author);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_author');
    }

    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor(ManagerRegistry $manager,AuthorRepository $repo,$id){
        $author = $repo->find($id);
        $manager->getManager()->remove($author);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_author');
    }

    #[Route('/author/update/{id}', name: 'app_author_update')]
    public function updateAuthor($id,ManagerRegistry $manager,AuthorRepository $repo){
        $author = $repo->find($id);
        $author->setUsername('author updated');
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_author');
    }

    #[Route('/adddAuthor', name: 'app_Author_add')]
    public function adddAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $Author = new Author();
        $form = $this->createForm(FormAuthorType::class, $Author); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Author); // Persist the book object
            $entityManager->flush(); // Save it to the database

            // Optionally, redirect to another route after saving
            // return $this->redirectToRoute('some_route_name');
        }

        return $this->render('Author/add.html.twig', [
            'A' => $form->createView()
        ]);
    }

}

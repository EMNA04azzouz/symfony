<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;

use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/book')]
class CrudBookController extends AbstractController
{






#[Route('/list', name: 'app_list_books')]
public function list(BookRepository $bookRepository): Response
{
    // Fetch all books from the repository
    $books = $bookRepository->findAll();


    // Pass the 'books' variable to the Twig template
    return $this->render('crud_book/listB.html.twig', [
        'books' => $books,  // Ensure this is being passed
    ]);
}





    #[Route('/new', name: 'app_new_book')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();

        // Create the form based on BookType
        $form = $this->createForm(BookType::class, $book);

        // Handle the form request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the book to the database
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_list_books');
        }

        // Render the form in the 'newB.html.twig' template, not 'listB.html.twig'
        return $this->render('crud_book/newB.html.twig', [
            'form' => $form->createView(),
        ]);
    }


#[Route('/{id}/edit', name: 'app_edit_book')]
public function edit(Request $request, Book $book, ManagerRegistry $doctrine): Response
{
    // Create the form based on BookType
    $form = $this->createForm(BookType::class, $book);

    // Handle the form request
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Save the updated book to the database
        $em = $doctrine->getManager();
        $em->flush();  // No need to persist again, just flush changes

        return $this->redirectToRoute('app_list_books');
    }

    // Render the form in a new template for editing
    return $this->render('crud_book/editB.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/{id}/delete', name: 'app_delete_book')]
public function delete(Request $request, Book $book, ManagerRegistry $doctrine): Response
{
    // If a confirmation is needed, you can handle it here
    if ($request->isMethod('POST')) {
        $em = $doctrine->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('app_list_books');
    }

    // Render a confirmation template (optional)
    return $this->render('crud_book/deleteB.html.twig', [
        'book' => $book,
    ]);
}



}
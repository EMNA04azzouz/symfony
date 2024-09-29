<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    private $authors;

    public function __construct()
    {
        $this->authors = [
            [
                        'id' => 1,
                        'name' => 'Taha Hussain',
                        'picture' => 'images/th.jpeg',  // Image existante
                        'nbrBooks' => 5,
                        'biography' => 'Taha Hussain était un écrivain et intellectuel éminent dans le monde arabe..',
                        'born' => '1889-11-14',
                        'nationality' => 'egyptien',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Victor Hugo',
                        'picture' => 'images/vh.jpeg',  // Image existante
                        'nbrBooks' => 40,
                        'biography' => 'Victor Hugo était un poète, romancier et dramaturge français du mouvement romantique..',
                        'born' => '1802-02-26',
                        'nationality' => 'français',
                    ],
                    ];
    }

    #[Route("/library", name: "app_library", methods: ["GET"])]
    public function index()
    {
        return $this->render('author/index.html.twig');
    }

    #[Route("/author/{name}", name: "app_author", methods: ["GET"], defaults: ["name" => "taha hussain"])]
    public function showAuthor($name)
    {
        return $this->render('author/show.html.twig', [
            'name' => $name
        ]);
    }

    // return list of authors
    #[Route("/list", name: "app_list", methods: ["GET"])]
    public function authorList()
    {
        return $this->render('author/list.html.twig', [
            'authors' => $this->authors
        ]);
    }

       #[Route("/showDetails/{id}", name: "app_showDetail", methods: ["GET"])]
       public function showDetailsAction($id): Response
       {
           $author = null;

           foreach ($this->authors as $a) {
               if ($a['id'] == $id) {
                   $author = $a;
                   break;
               }
           }

           if (!$author) {
               throw $this->createNotFoundException('Author not found');
           }

           return $this->render("author/show.html.twig", [
               'author' => $author,
           ]);
       }
   }
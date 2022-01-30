<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * route qui affiche la liste des films de la BDD
     * 
     * @Route("/", name="list")
     * 
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('home/list.html.twig');
    }

    /**
     * route servant à ajouter un film
     * 
     * @Route("/add", name="add")
     *
     * @return Response
     */
    public function add()
    {
        return $this->render('home/add.html.twig');
    }
}

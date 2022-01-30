<?php

namespace App\Controller;

use App\Form\VideoType;
use App\Service\CallApi;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{ Request, Response };
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
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
    public function add(CallApi $callApi, Request $request)
    {
        // on crée notre formulaire
        $form = $this->createForm(VideoType::class);

        // on permet à notre formulaire d'accéder à la requête
        $form->handleRequest($request);

        // si la méthode est post on transmet le titre à l'api
        if ($request->isMethod('post'))
        {
            if ($form->isSubmitted() && $form->isValid())
            {
                // on récupère le contenu de $_POST
                $POST = $request->request->get('video');
                $title = $POST['title'];

                // on transmet le titre à l'api
                $movie = $callApi->getMovieByTitle($title);
                
                // s'il n'y a pas de film
                if ($movie['Response'] === 'False')
                {
                    // TODO renvoyer un message pour signaler que le titre n'a pas été trouvé (utiliser la session ?)
                    return new Response('erreur lors de la soumission du titre');
                }
                // on met l'objet Movie en session (l'enregistrement est différé)
                $this->session->set('movie', $movie);
                
                return $this->renderForm('home/add.html.twig', compact('form', 'movie'));
            }
        }
        return $this->renderForm('home/add.html.twig', compact('form'));
    }

    public function save()
    {
        // si on arrive ici c'est que l'on veut enregistrer le film dans la session
        $movie = $this->session->get('movie');

        // on prépare l'insertion en BDD


        // on supprime l'objet movie de la session
        $this->session->remove('movie');

        // on redirige vers la page de recherche
        $this->redirectToRoute('add');
    }
}
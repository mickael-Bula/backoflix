<?php

namespace App\Controller;

use App\Util\Util;
use App\Form\VideoType;
use App\Service\CallApi;
use App\Entity\{ Movie, Actor, Genre, Season };
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{ Request, Response };
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        // TODO créer le template pour afficher la liste des films depuis la BDD

        return $this->render('home/list.html.twig');
    }

    /**
     * route servant à ajouter un film
     * 
     * @Route("/add", name="add", methods={"GET", "POST"})
     * 
     * @return Response
     */
    public function add(CallApi $callApi, Request $request): Response
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
                    return $this->render('home/list.html.twig');
                }
                // on met l'objet Movie en session (l'enregistrement est différé)
                $this->session->set('movie', $movie);
                
                return $this->renderForm('home/add.html.twig', compact('form', 'movie'));
            }
        }
        return $this->renderForm('home/add.html.twig', compact('form'));
    }

    /**
     * méthode qui insère un film en BDD
     * 
     * @Route("/save", name="save", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function save(EntityManagerInterface $manager): Response
    {
        // si on arrive ici c'est que l'on veut enregistrer le film dans la session
        $movie = $this->session->get('movie');

        // on crée un nouvel objet de la classe Movie
        $newMovie = new Movie();

        // on prépare son insertion en BDD
        $newMovie->setTitle($movie['Title']);
        $newMovie->setType($movie['Type']);
        $newMovie->setRating($movie['imdbRating']);
        $newMovie->setPoster($movie['Poster']);
        $newMovie->setSummary($movie['Plot']);

        // on récupère la partie numérique de duration
        $runtime = explode(' ', $movie['Runtime'])[0];
        $newMovie->setDuration($runtime);
        
        // on convertit au format Datetime
        $releasedDate = new \DateTime($movie['Released']);
        $newMovie->setReleasedDate($releasedDate);

        // on définit le synopsis comme une sous-chaîne de Summary
        $synopsis = \substr($movie['Plot'], 0, 50);
        $newMovie->setSynopsis($synopsis);

        // on enregistre les acteurs
        $actors = Util::splitArray($movie['Actors']);
        foreach($actors as $actor)
        {            
            [$firstname, $lastname] = explode(' ', $actor);
            
            $newActor = new Actor();
            $newActor->setFirstname($firstname);
            $newActor->setLastname($lastname);

            $manager->persist($newActor);
        }

        // on fait de même avec les genres
        $genres = Util::splitArray($movie['Genre']);
        foreach ($genres as $genre)
        {
            $newGenre = new Genre();

            $newGenre->setName($genre);
            $newGenre->addMovie($newMovie);

            $manager->persist($newGenre);
        }

        // si totalSeasons existe on l'enregistre
        if (isset($movie['totalSeasons']))
        {
            $numberSeason = (int) $movie['totalSeasons'];

            $newSeason = new Season();
            $newSeason->setNumber($numberSeason);

            $manager->persist($newSeason);

            $newMovie->addSeason($newSeason);
        }

        // on persiste l'objet
        $manager->persist($newMovie);

        // on enregistre en BDD
        $manager->flush();

        // on supprime l'objet movie de la session
        $this->session->remove('movie');

        // on redirige vers la page de recherche
        return $this->redirectToRoute('add');
    }
}
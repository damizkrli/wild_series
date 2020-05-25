<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="wild_index")
     * @return Response
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("/show/{slug<[0-9a-z]+$>}", name="show_slug", defaults={"slug" = null})
     * @param string $slug
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug){
            $slug = "Aucune série sélectionnée, veuillez choisir une série";
        }

        $slug = str_replace("-", ' ', ucwords(trim(strip_tags($slug)), '-'));
        return $this->render('wild/show.html.twig', ['slug' => $slug]);
    }
}

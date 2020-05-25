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
     * @Route("/show/{slug}",
     *     requirements={"slug"="[a-z-]+"},
     *     defaults={"slug" = "Aucune série sélectionnée, veuillez choisir une série"},
     *     name="_show")
     */
    public function show(string $slug) :Response
    {
        if ($slug != "Aucune série sélectionnée, veuillez choisir une série"){
            $slug = ucwords(str_replace('-', ' ', $slug));
        }

        return $this->render('wild/show.html.twig', [
            'slug' => $slug,
        ]);
    }
}

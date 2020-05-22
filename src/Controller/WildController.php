<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     * @return Response
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("wild/show/{slug}", requirements={"slug"="[0-9a-z]+"}, name="show_slug")
     * @return Response
     */
    public function show($slug = "Veuillez choisir une série"): Response
    {
        $slug = ucwords(str_replace('-', ' ', $slug));
        return $this->render('wild/show.html.twig', ['slug' => $slug]);
    }
}

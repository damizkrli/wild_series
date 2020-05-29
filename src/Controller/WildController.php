<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig',
            ['programs' => $program]
        );
    }

    /**
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     *
     */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = str_replace("-", ' ', ucwords(trim(strip_tags($slug)), "-"));
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName}", name="wild_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory (string $categoryName): Response {
        if (!$categoryName) {
            throw $this
            ->createNotFoundException('No slug has been sent to find a category in catgory\'s name');
        }
        $categoryName = str_replace("-", ' ', ucwords(trim(strip_tags($categoryName))));
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);

        $selectByCategory = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        return $this->render('wild/category.html.twig',[
            'selectByCategory' => $selectByCategory,
            'category' => $category,
        ]);
    }

    /**
     * @param string $slug
     * @Route("/showByProgram/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="showByProgram"))
     * @return Response
     */
    public function showByProgram(string $slug): Response
    {

        $slug = str_replace("-", ' ', ucwords(trim(strip_tags($slug)), "-"));
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneByTitle($slug);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render('wild/showByProgram.html.twig', [
            'program' => $program,
            'slug' => $slug,
            'seasons' => $seasons,
            ]);
    }

    /**
     * @param int $id
     * @Route("/showBySeason/{id}", requirements={"id"="\d+"}, name="showBySeason")
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $id], ['id' => 'ASC',]);

        return $this->render('wild/showBySeason.html.twig', [
            'season' => $season,
            'episodes' => $episodes,
            'program' => $season->getProgram(),
            ]);
    }


}

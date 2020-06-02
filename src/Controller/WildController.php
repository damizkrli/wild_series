<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramSearchType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/wild")
 * Class WildController
 * @package App\Controller
 *
 */
class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/", name="wild_index")
     * @param ProgramRepository $programRepository
     * @param Request $request
     * @return Response
     */
    public function index(ProgramRepository $programRepository, Request $request): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $program,
            ]);
    }

    /**
     *
     * @param string $slug
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     *
     */
    public function show(?string $slug): Response
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
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName}", name="category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
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
                6
            );

        return $this->render('wild/category.html.twig', [
            'selectByCategory' => $selectByCategory,
            'category' => $category,
        ]);
    }

    /**
     * @param string $slug
     * @Route("/program/{slug<^[a-zA-Z-]+$>}", name="show_program")
     * @return Response
     */
    public function showByProgram(string $slug): Response
    {

        $slug = str_replace("-", ' ', ucwords(trim(strip_tags($slug)), "-"));
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => $slug]);

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
     * @param SeasonRepository $seasonRepository
     * @param int $season_id
     * @return Response
     * @Route("/show-by-season/{season_id}", requirements={"id"="\d+"}, name="show_season")
     */
    public function showBySeason(SeasonRepository $seasonRepository, int $season_id): Response
    {
        $season = $seasonRepository->findOneBy(['id' => $season_id]);

        return $this->render('wild/showBySeason.html.twig', [
            'season' => $season,
            'episodes' => $season->getEpisodes(),
            'program' => $season->getProgram(),
        ]);
    }

    /**
     * @Route("/wild/{slug<^[a-zA-Z-]+$>}/season/{season_id<^[0-9]+$>}/episode/{episode_id<^[0-9]+$>}",
     *     name="show_episode")
     * @ParamConverter("episode", options={"mapping":{"episode_id":"id"}})
     * @param int $season_id
     * @param Episode $episode
     * @param SeasonRepository $seasonRepository
     * @return Response
     */
    public function showEpisode(int $season_id, Episode $episode, SeasonRepository $seasonRepository): Response
    {
        $season = $seasonRepository->findOneBy(['id' => $season_id]);

        return $this->render('wild/showOneEpisode.html.twig', [
            'episode' => $episode,
            'season' => $episode->getSeason(),
            'program' => $season->getProgram(),
        ]);
    }


}

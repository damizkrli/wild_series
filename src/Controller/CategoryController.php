<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route ("/category", name="index_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/success/{id}", name="category")
     * @param int $id
     * @return Response
     */
    public function success(int $id): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['id' => $id]);

        return $this->render('wild/listCategory.html.twig', [
            'category' => $category,
            ]);
    }

    /**
     * @Route ("/add", name="add_category")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('index_category',
                ['id' => $category->getId()
            ]);
        }

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('wild/addCategory.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }
}
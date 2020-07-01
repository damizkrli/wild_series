<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use App\Service\MessagesFlash;
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/actor")

 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="actor_index", methods={"GET"})
     * @param ActorRepository $actorRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(
        ActorRepository $actorRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $paginator->paginate(
                $actorRepository->findAll(),
                $request->query->getInt('page', 1),
                5
            )
        ]);
    }

    /**
     * @Route("/new", name="actor_new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @param MessagesFlash $messagesFlash
     * @return Response
     * @isGranted("ROLE_ADMIN")
     * @isGranted("ROLE_USER")
     */
    public function new(
        Request $request,
        Slugify $slugify,
        MessagesFlash $messagesFlash
    ): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->addFlash('create', $messagesFlash->create('actor'));
            $actor->setSlug($slugify->generate($actor->getName()));
            $entityManager->persist($actor);
            $entityManager->flush();

            return $this->redirectToRoute('actor_index');
        }

        return $this->render('actor/new.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug<[a-zA-Z-]+>}", name="actor_show", methods={"GET"})
     * @param Actor $actor
     * @return Response
     * @isGranted("ROLE_ADMIN")
     * @isGranted("ROLE_USER")
     */
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="actor_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Actor $actor
     * @param Slugify $slugify
     * @param MessagesFlash $messagesFlash
     * @return Response
     * @isGranted("ROLE_ADMIN")
     * @isGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        Actor $actor,
        Slugify $slugify,
        MessagesFlash $messagesFlash
    ): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actor->setSlug($slugify->generate($actor->getName()));
            $this->addFlash('update', $messagesFlash->update('actor'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actor_index');
        }

        return $this->render('actor/edit.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="actor_delete", methods={"DELETE"})
     * @param Request $request
     * @param Actor $actor
     * @param MessagesFlash $messagesFlash
     * @return Response
     * @isGranted("ROLE_ADMIN")
     * @isGranted("ROLE_USER")
     */
    public function delete(
        Request $request,
        Actor $actor,
        MessagesFlash $messagesFlash
): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actor);
            $this->addFlash('delete', $messagesFlash->delete('actor'));
            $entityManager->flush();
        }

        return $this->redirectToRoute('actor_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\MessagesFlash;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_index", methods={"GET"})
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="program_new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @param MailerInterface $mailer
     * @param MessagesFlash $messagesFlash
     * @return void
     */
    public function new(
        Request $request,
        Slugify $slugify,
        MailerInterface $mailer,
        MessagesFlash $messagesFlash
): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $program->setSlug($slugify->generate($program->getTitle()));
            $this->addFlash('create', $messagesFlash->create('program'));
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from('karli.damien@gmail.com')
                ->to('karli.damien@gmail.com')
                ->subject('A new series has just been published')
                ->html($this->renderView('program/email/notification.html.twig', [
                    'program' => $program,
                    ]), 'utf-8');

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_show", methods={"GET"})
     * @param Program $program
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="program_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Program $program
     * @param Slugify $slugify
     * @param MessagesFlash $messagesFlash
     * @return Response
     */
    public function edit(
        Request $request,
        Program $program,
        Slugify $slugify,
        MessagesFlash $messagesFlash
    ): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program->setSlug($slugify->generate($program->getTitle()));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $messagesFlash->update('program'));

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_delete", methods={"DELETE"})
     * @param Request $request
     * @param Program $program
     * @param MessagesFlash $messagesFlash
     * @return Response
     */
    public function delete(Request $request, Program $program, MessagesFlash $messagesFlash): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->addFlash('delete', $messagesFlash->delete('program'));
            $entityManager->remove($program);
            $entityManager->flush();

        }

        return $this->redirectToRoute('program_index');
    }
}

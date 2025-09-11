<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/wish', name: 'wish_')]
final class WishController extends AbstractController
{
    #[Route('/list', name: "list", methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();
        return $this->render(
            'wish/list.html.twig',
            ['wishes' => $wishes]
        );
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        return $this->render(
            'wish/detail.html.twig',
            ['wish' => $wish]
        );
    }

    #[Route('/create', name: "create_form", methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/img')] string $imageDirectory
    ): Response {
        $wish = new Wish();
        $createForm = $this->createForm(WishType::class, $wish);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $image = $createForm->get('image')->getData();


            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                }

                $wish->setImageName($newFilename);
            }

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Idea successfully added ');
            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
        }

        return $this->render(
            'wish/create-form.html.twig',
            ['createForm' => $createForm]
        );
    }

    #[Route('/{id}/modify', name: "modify_form", methods: ['GET', 'POST'])]
    public function update(
        Wish $wish,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/img')] string $imageDirectory
    ): Response {
        $editForm = $this->createForm(WishType::class, $wish);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Récupération du fichier après handleRequest
            $image = $editForm->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                }

                $wish->setImageName($newFilename);
            }

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Idea successfully updated!');
            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
        }

        return $this->render('wish/edit-form.html.twig', [
            'editForm' => $editForm,
            'wish' => $wish

        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(int $id, WishRepository $wishRepository, EntityManagerInterface $em): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            $this->addFlash('error', 'Wish not found');
            return $this->redirectToRoute('wish_list');
        }

        $em->remove($wish);
        $em->flush();

        $this->addFlash('success', 'Idea successfully removed');

        return $this->redirectToRoute('wish_list');
    }

}
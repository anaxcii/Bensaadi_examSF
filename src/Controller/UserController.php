<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            "title" => "Gestion"
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, FileUploader $fileUploader, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,['required'=>true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($user->getContrat() === 'Interim' || $user->getContrat() === 'CDD') && $user->getSortie() == null) {
                $form->addError(new FormError("Veuillez renseigner une date de sortie"));
            } else {


                /** @var UploadedFile $image */
                $image = $form->get('image')->getData();
                if ($image) {
                    $res = $fileUploader->upload($image, $fileUploader->getUserDirectory());
                    $user->setImage('uploads/user/' . $res);
                }

                $user->setRoles(["ROLE_USER"]);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/new.html.twig', [
            'form' => $form,
            "title" => "Création"
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, FileUploader $fileUploader, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user,['required'=>false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($user->getContrat() === 'Interim' || $user->getContrat() === 'CDD') && $user->getSortie() == null) {
                $form->addError(new FormError("Veuillez renseigner une date de sortie"));
            } else {
                /** @var UploadedFile $image */
                $image = $form->get('image')->getData();
                if ($image) {
                    $res = $fileUploader->upload($image, $fileUploader->getUserDirectory());
                    $fileUploader->removeFile($user->getImage());
                    $user->setImage('uploads/user/' . $res);
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(FileUploader $fileUploader, Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            $fileUploader->removeFile($user->getImage());
            $userRepository->remove($user, true);

            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

}

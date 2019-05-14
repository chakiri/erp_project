<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Profile controller.
 *
 * @Route("my_account")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile_index")
     */
    public function index(ProfileRepository $profileRepository)
    {
        $user = $this->getUser();

        $profile = $profileRepository->findOneBy(['user' => $user]);

        if ($user){
            return $this->render('profile/show.html.twig', [
                'profile' => $profile
            ]);
        }
    }

    /**
     * @Route("/edit", name="profile_edit")
     */
    public function form(Request $request, ObjectManager $manager, ProfileRepository $profileRepository)
    {
        $user = $this->getUser();

        $profile = $profileRepository->findOneBy(['user' => $user]);

        //$profile->getUser()->setConfirmPassword($user->getPassword());

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($profile);

            $manager->flush();

            return $this->redirectToRoute("profile_index");
        }

        return $this->render('profile/edit.html.twig', [
            'formProfile' => $form->createView()
        ]);
    }
}

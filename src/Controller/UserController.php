<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User controller.
 *
 * @Route("my_account")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user_index")
     */
    public function index()
    {
        $user = $this->getUser();

        if ($user){
            return $this->render('user/show.html.twig', [
                'user' => $user
            ]);
        }
    }

    /**
     * @Route("/edit", name="user_edit")
     */
    public function edit(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $user->setConfirmPassword($user->getPassword());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);

            $manager->flush();

            return $this->redirectToRoute("user_index");
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

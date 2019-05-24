<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encrypt)
    {
        $user = new User();

        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encrypt->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);

            $profile = new Profile();
            $profile->setUser($user);

            $manager->persist($profile);

            $manager->flush();

            return $this->redirectToRoute('profile_edit');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){}
}

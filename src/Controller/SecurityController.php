<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encrypt)
    {
        $user = new User();

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

            //login user automaticlly
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));

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

    /**
     * @Route("/forgottenPassword", name="security_forgotten_password")
     */
    public function forgottenPassword(Request $request, ObjectManager $manager, UserRepository $userRepository,  TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mailer)
    {
        if ($request->isMethod('POST')){
            $email = $request->get('email');

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user){
                $this->addFlash('danger', 'Email not exist');

                return $this->redirectToRoute('security_forgotten_password');
            }

            $token = $tokenGenerator->generateToken();

            $user->setResetToken($token);

            $manager->persist($user);

            $manager->flush();

            $url = $this->generateUrl('security_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message())
                ->setSubject('Reset Password')
                ->setFrom('no-reply@myerp.com')
                ->setTo($email)
                ->setBody('For reseting your password please follow the link below : ' . $url, 'text/html')
                ;

            $mailer->send($message);

            $this->addFlash('success', 'A email has been sent');

            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/forgottenPassword.html.twig');
    }

    /**
     * @Route("/resetPassword/{token}", name="security_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserRepository $userRepository, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        if ($request->isMethod('POST')){
            $user = $userRepository->findOneBy(['resetToken' => $token]);

            if (!$user){
                $this->addFlash('danger', 'Token unknown');

                return $this->redirectToRoute('security_login');
            }

            $user->setResetToken(null);
            $user->setPassword($encoder->encodePassword($user, $request->get('password')));

            $manager->persist($user);

            $manager->flush();

            $this->addFlash('success', 'The password has been modified');

            return $this->redirectToRoute('security_login');

        }
        return $this->render('security/resetPassword.html.twig', [
            'token' => $token
        ]);
    }
}

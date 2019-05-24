<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_index")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/active/{id}", name="user_active")
     */
    public function activeUser(User $user, ObjectManager $manager, AuthorizationCheckerInterface $authChecker)
    {
        if (false === ($authChecker->isGranted('ROLE_SUPER_ADMIN') || $authChecker->isGranted('ROLE_ADMIN'))){
            return $this->json([
                'code' => '403',
                'message' => 'access denied'
            ], 403);
        }

        if ($user->getIsValid() == true){
            $user->setIsValid(false);

            $manager->persist($user);

            $manager->flush();

            return $this->json([
                'code' => '201',
                'message' => 'user desactivate',
                'isValid' => $user->getIsValid()
            ], 201);
        }else{
            $user->setIsValid(true);

            $manager->persist($user);

            $manager->flush();

            return $this->json([
                'code' => '200',
                'message' => 'user activate',
                'isValid' => $user->getIsValid()
            ], 200);
        }
    }
}

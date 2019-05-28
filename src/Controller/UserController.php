<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserController
 * @Route("user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/active/{id}", name="user_active")
     */
    public function activeUser(User $user, ObjectManager $manager, AuthorizationCheckerInterface $authChecker)
    {
        if (false === ($authChecker->isGranted('ROLE_ADMIN'))){
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

    /**
     * @Route("/role", name="user_role")
     */
    public function roleUser(UserRepository $userRepository, ObjectManager $manager, AuthorizationCheckerInterface $authChecker)
    {
        $userId = $_POST['id'];
        $option = $_POST['option'];

        $user = $userRepository->findOneBy(['id' => $userId]);

        if (false === ($authChecker->isGranted('ROLE_ADMIN'))){
            return $this->json([
                'code' => '403',
                'message' => 'access denied'
            ], 403);
        }

        switch ($option) {
            case 1 :
                $user->setRoles(['ROLE_SUPER_ADMIN']);
                break;
            case 2 :
                $user->setRoles(['ROLE_ADMIN']);
                break;
            case 3 :
                $user->setRoles(['ROLE_USER']);
                break;
        }

        $manager->persist($user);

        $manager->flush();

        return $this->json([
            'code' => '201',
            'message' => 'role changed',
            'role' => $user->getRoles()
        ], 201);

    }
}

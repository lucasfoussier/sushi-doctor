<?php
namespace App\Controller;

use App\Entity\User;
use App\Message\SmsNotification;
use App\Repository\UserRepository;
use App\Service\ResponseService;
use DateTime;
use JLucki\ODM\Spark\Spark;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{

    public function __construct(
        private Spark $spark,
    ){}

    #[
        Route("/api", name:"home")
    ]
    public function index(
        UserRepository $userRepository
    ): Response
    {
        $fetchedUser = $userRepository->findOneByEmail('lucasfoussier@gmail.com');
        return new JsonResponse([
            'fetchedUser' => $fetchedUser->getId(),
            'user' => $this->getUser()->getUsername()
        ]);
//        die('fdsq');

    }

    #[
        Route("/api/message", name:"message")
    ]
    public function message(
        MessageBusInterface $bus,
        ResponseService $responseService
    ): Response
    {

        $bus->dispatch(new SmsNotification('Look! I created a message!'));

//        or use the shortcut
//        $this->dispatchMessage(new SmsNotification('Look! I created a message!'));

        return $responseService->getOk();
    }


    #[
        Route("/test", name:"test")
    ]
    public function test(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $user = new User();


        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                'test'
            )
        );

        $user->setEmail('lucasfoussier@gmail.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $userRepository->insertNewUser($user);







        return new JsonResponse(['ok']);
//        die('fdsq');

    }


    /**
     * @Route("/{req}", name="webpack", requirements={"req"="^((?!api).)*$"})
     */
    public function webpack(): Response
    {
        return $this->render('default/index.html.twig', []);
    }



}

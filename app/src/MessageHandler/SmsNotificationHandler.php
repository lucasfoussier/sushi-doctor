<?php
namespace App\MessageHandler;

use App\Entity\User;
use App\Message\SmsNotification;
use App\Repository\UserRepository;
use JLucki\ODM\Spark\Spark;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{

    public function __construct(
        private Spark $spark,
        private UserRepository $userRepository
    ) {}

    public function __invoke(SmsNotification $message)
    {
        $user = new User();
        $user->setPassword('fdsq');
        $user->setEmail(uniqid());
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->userRepository->insertNewUser($user);
        // ... do some work - like sending an SMS message!
        return true;
    }
}

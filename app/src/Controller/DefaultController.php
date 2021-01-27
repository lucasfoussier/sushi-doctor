<?php
namespace App\Controller;

use Aws\DynamoDb\DynamoDbClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DynamoDb\Model;
use App\DynamoDb\User;

class DefaultController extends AbstractController
{

    /**
     * @var Model
     */
    private $dynamoDbModel;

    /**
     * @var DynamoDbClient
     */
    private $dynamoDbClient;

    public function __construct(Model $model, DynamoDbClient $dynamoDbClient)
    {
        $this->dynamoDbModel = $model;
        $this->dynamoDbClient = $dynamoDbClient;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $user = new User();
        $user->setFirstName("hello");
        $user->setLastName("world");
        $user->save();
        $user->getId();

        $user = User::getById($user->getId());
        $user->setLastName("wood");
        $user->save();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }



}

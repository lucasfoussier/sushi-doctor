<?php
namespace App\Controller;

use DateTime;
use JLucki\ODM\Spark\Spark;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DynamoDb\Article;

class DefaultController extends AbstractController
{

    public function __construct(
        private Spark $spark,
    ){}

    #[
        Route("/", name:"home")
    ]
    public function index(): Response
    {

        $date = new DateTime();

        $blog = new Article();
        $blog
            ->setType('blog')
            ->setDatetime($date)
            ->setSlug('my-blog-post-' . $date->format('y-m-d-H-i-s'))
            ->setTitle('My Blog Post ' . $date->format('Y-m-d H:i:s'))
            ->setContent('Hello, this is the blog post content.')
        ;
        $this->spark->putItem($blog);





        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }



}

<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends AbstractController
{


    private ProductRepository $productRepository;

    public function __construct(ProductRepository      $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    function buildTree($elements, int $parentId = 0): array
    {
        $branch = array();

        $d = [];
        foreach ($elements as $category) {

            if (!$category->getChildren()) {
                $category->parentId = 0;
                array_push($d, $category);
            } else {
                $category->parentId = $category->getChildren()->getId();
                array_push($d, $category);
            }
        }

        foreach ($d as $element) {

            if ($element->parentId == $parentId) {
                $children = $this->buildTree($elements, $element->getId());
                if ($children) {
                    $element->childrenn = $children;
                } else {
                    $element->childrenn = array();
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }


    #[Route('/page', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirect('/');
        }
        $products = $doctrine->getRepository(Product::class)->findAll();
        $categories = $doctrine->getRepository(Category::class)->findAll();
        $data = $this->buildTree($categories);


        return $this->render('frontend/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'categories' => $data
        ]);
    }



    #[Route('/category/{name}', name: 'app_category')]
    public function category(ManagerRegistry $doctrine,  String $name): Response
    {
        if (!$this->getUser()) {
            return $this->redirect('/');
        }

        $data = $doctrine->getRepository(Category::class)->findOneBy(['name' => $name]);
        $conn = $doctrine->getConnection();

        $sql = 'SELECT *  FROM category 
        LEFT JOIN product_category ON category.id = product_category.category_id
        where product_category.category_id = :id ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $data->getId()]);
        $query =  $resultSet->fetchAllAssociative();

        $product_ids = [];
        foreach ($query as $key => $value) {
            array_push($product_ids, $value['product_id']);
        }
        $products = [];
        foreach ($product_ids as $key => $value) {
            array_push($products, $doctrine->getRepository(Product::class)->findOneBy(['id' => $value]));
        }

        return $this->render('frontend/category.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'categories' => $data
        ]);
    }


    #[Route('/addsession', name: 'app_addsession', methods: ['POST'])]
    public function addProductSession(ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $this->get('session');


        if ($session->get('basket')) {
            $basket =  $session->get('basket');
        } else {
            $basket = [];
        }

        array_push($basket, $request->get('productId'));
        $session->set('basket', $basket);


        $products = $doctrine->getRepository(Product::class)->findAll();
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->redirect('/page');

        // return $this->render('frontend/index.html.twig', [
        //     'controller_name' => 'HomeController',
        //     'products' => $products,
        //     'categories' => $categories,
        //     'addProductStatus' => TRUE,
        // ]);
    }




    #[Route('/check-out', name: 'app_checkOut')]
    public function getCheckOutPage(): Response
    {

        $session = $this->get('session');
        return $this->render('frontend/check-out.html.twig', [
            'controller_name' => 'HomeController',
            'baskets' => $session->get('totalBasket'),
            'user' => $this->getUser()
        ]);
    }

    #[Route('/tracking', name: 'app_tracking')]
    public function getTrackingPage(ManagerRegistry $doctrine): Response
    {
        $baskets = $doctrine->getRepository(Basket::class)->findBy(['user' => $this->getUser()->getId()], ['id' => 'DESC']);

        return $this->render('frontend/tracking.html.twig', [
            'controller_name' => 'HomeController',
            'baskets' => $baskets
        ]);
    }


    #[Route('/check-out/save', name: 'app_checkOut-save')]
    public function getCheckOutSave(ManagerRegistry $doctrine, Request $request): Response
    {

        $session = $this->get('session');
        $data = [];
        foreach (array_count_values($session->get('basket')) as $key => $value) {
            array_push($data, $key);
        }

        $entityManager = $doctrine->getManager();
        $basket = new Basket();

        $basket->setUser($this->getUser());
        $basket->setTotalPrice($session->get('totalBasket')[0]['basketTotalPrice']);
        $basket->setAddress($request->get('address'));
        $date = new \DateTime('@' . strtotime('now'));
        $basket->setCreatedAt($date);
        $basket->setStatus("0");
        $basket->setCheckMoney("0");
        $basket->setProductId($data);

        try {

            $entityManager->persist($basket);
            $entityManager->flush();

            $baskets = $doctrine->getRepository(Basket::class)->findBy(['user' => $this->getUser()->getId()], ['id' => 'DESC']);
            $session = $this->get('session');
            $session->clear();
            return $this->render('frontend/tracking.html.twig', [
                'controller_name' => 'HomeController',
                'baskets' => $baskets
            ]);
        } catch (\Throwable $th) {
            return $this->render('frontend/basket.html.twig', [
                'message' => FALSE
            ]);
        }
    }
}

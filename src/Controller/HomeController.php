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

    public function __construct(ProductRepository      $productRepository,)
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

        return $this->redirect('/');

        // return $this->render('frontend/index.html.twig', [
        //     'controller_name' => 'HomeController',
        //     'products' => $products,
        //     'categories' => $categories,
        //     'addProductStatus' => TRUE,
        // ]);
    }


    #[Route('/basket', name: 'app_basket')]
    public function getBasketPage(ManagerRegistry $doctrine): Response
    {

        if (!$this->getUser()) {
            return $this->redirect('/');
        }
        $categories = $doctrine->getRepository(Category::class)->findBy(['active' => 1]);

        $conn = $doctrine->getConnection();
        $saleProductsId = [];
        foreach ($categories as $key => $category) {
            $sql = 'SELECT *  FROM category 
            LEFT JOIN product_category ON category.id = product_category.category_id
            where product_category.category_id = :id ';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery(['id' => $category->getId()]);
            $query =  $resultSet->fetchAllAssociative();

            foreach ($query as $que) {
                array_push($saleProductsId, $que['id']);
            }
        }

        $session = $this->get('session');
        $conn = $doctrine->getConnection();
        $i = 0;
        $totalPrice = 0;

        // dd( "sales" ,  $saleProductsId  , "basket" , array_count_values($session->get('basket')) );


        if ($session->get('basket')) {

            foreach (array_count_values($session->get('basket')) as $key => $value) {

                // $sql = '   SELECT * FROM product p WHERE p.id = :id ';
                // $stmt = $conn->prepare($sql);
                // $resultSet = $stmt->executeQuery(['id' => $key]);
                // $data[$i] = $resultSet->fetchAllAssociative()[0];

                $sql = 'SELECT *  FROM product 
                LEFT JOIN product_category ON product.id = product_category.product_id
                where product_category.product_id = :id ';
                $stmt = $conn->prepare($sql);
                $resultSet = $stmt->executeQuery(['id' => $key]);
                $data[$i]   =  $resultSet->fetchAllAssociative()[0];
                
                foreach ($saleProductsId as $sale) {
                    // echo $sale . "   " . $key . "<br>";


                    if ((int)$sale  ==  $data[$i]['category_id'] ) {
                        if ($value >= 3) {
                            $data[$i]['3-1sale'] = TRUE;
                        }
                    }
                }

                if ($value > 2) {
                    $data[$i]['2-%50sale'] = TRUE;
                }

                $data[$i]['quantity'] = $value;
                $data[$i]['totalPrice'] = $value * $data[$i]['price'];


                $i++;
            }

         
            $enk =  $data[0]['price'];
            $enkPriceKey = 0;

            foreach ($data as $key => $dat) {

                if (isset($dat['3-1sale'])) {
                    $sale3_1 =     $dat['totalPrice'] -  $dat['price'];
                    $saleId =  $key;
                }

                if ((int)$enk > (int) $dat['price']) {

                    $enkPriceKey =  $key;
                    $enk = $dat['price'];
                }
            }

            if (isset($saleId)) {
                $data[$saleId]['totalPrice'] = $sale3_1;
                // echo "3-1 indirimi yapıldı";
            } else {

                foreach ($data as $key => $dat) {

                    if (isset($dat['2-%50sale'])) {
                        $sale2_50 = $data[$enkPriceKey]['totalPrice'] - ($enk / 2);
                        $dat['totalPrice'] =  $dat['totalPrice'] / 2;
                    }
                }

                if (isset($sale2_50)) {
                    // echo "2-%50 indirimi yapıldı";
                    $data[$enkPriceKey]['totalPrice'] = $sale2_50;
                } else {
                    //  echo "herhangi bi indirim olmadı";
                }
            }


            //  dd($data, $sale3_1, $enk,    $data[$enkPriceKey]['totalPrice']);

            foreach ($data as $dat) {
                $totalPrice = $totalPrice +   $dat['totalPrice'];
            }
        }


        if (isset($data)) {
            $data[0]['basketTotalPrice'] = $totalPrice;
            $session = $this->get('session');
            $session->set('totalBasket',   $data);
        } else {
            $session->clear();
        }

        return $this->render('frontend/basket.html.twig', [
            'controller_name' => 'HomeController',
            'baskets' => $session->get('totalBasket')
        ]);
    }


    #[Route('/basket/minus', name: 'app_minusBasket')]
    public function minusBasket(ManagerRegistry $doctrine): Response
    {
        $data = [];
        $session = $this->get('session');

        $totalPrice = 0;
        $basket = [];
        foreach ($session->get('totalBasket') as  $key => $value) {

            if ($value['id'] == $_GET['productId']) {
                $value['quantity'] = $value['quantity'] - 1;
                $value['totalPrice'] = $value['quantity'] * $value['price'];
                if ($value['quantity'] > 0) {
                    array_push($data, $value);

                    for ($i = 0; $i < $value['quantity']; $i++) {
                        array_push($basket, $value['id']);
                    }
                }
            } else {
                array_push($data, $value);

                for ($i = 0; $i < $value['quantity']; $i++) {
                    array_push($basket, $value['id']);
                }
            }

            $totalPrice = $totalPrice + $value['totalPrice'];
        }
        $data[0]['basketTotalPrice'] = $totalPrice;
        $session->set('totalBasket',   $data);
        $session->set('basket',   $basket);


        return $this->redirect('/basket');

        // return $this->render('frontend/basket.html.twig', [
        //     'controller_name' => 'HomeController',
        //     'baskets' => $session->get('totalBasket')
        // ]);
    }

    #[Route('/basket/plus', name: 'app_plusBasket')]
    public function plusBasket(ManagerRegistry $doctrine): Response
    {
        $data = [];
        $session = $this->get('session');
        $i = 0;
        $totalPrice = 0;
        $basket = [];
        foreach ($session->get('totalBasket') as  $key => $value) {

            if ($value['id'] == $_GET['productId']) {
                $value['quantity'] = $value['quantity'] + 1;
                $value['totalPrice'] = $value['quantity'] * $value['price'];

                array_push($data, $value);
            } else {
                array_push($data, $value);
            }

            for ($i = 0; $i < $value['quantity']; $i++) {
                array_push($basket, $value['id']);
            }

            $totalPrice = $totalPrice + $value['totalPrice'];
        }
        $data[0]['basketTotalPrice'] = $totalPrice;
        $session->set('totalBasket',   $data);
        $session->set('basket',   $basket);

        return $this->redirect('/basket');

        // return $this->render('frontend/basket.html.twig', [
        //     'controller_name' => 'HomeController',
        //     'baskets' => $session->get('totalBasket')
        // ]);
    }

    #[Route('/basket/deleteProduct', name: 'app_deleteProduct')]
    public function deleteProduct(ManagerRegistry $doctrine): Response
    {
        $data = [];
        $session = $this->get('session');
        $i = 0;
        $totalPrice = 0;
        $basket = [];
        foreach ($session->get('totalBasket') as  $key => $value) {

            if ($value['id'] != $_GET['productId']) {
                array_push($data, $value);

                for ($i = 0; $i < $value['quantity']; $i++) {
                    array_push($basket, $value['id']);
                }
            }
            $totalPrice = $totalPrice + $value['totalPrice'];
        }
        $data[0]['basketTotalPrice'] = $totalPrice;

        $session->set('totalBasket',   $data);
        $session->set('basket',   $basket);

        return $this->redirect('/basket');

        // return $this->render('frontend/basket.html.twig', [
        //     'controller_name' => 'HomeController',
        //     'baskets' => $session->get('totalBasket')
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

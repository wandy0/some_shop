<?php
    #require_once __DIR__ . '/../vendor/autoload.php';

    namespace App\Controller;
    use App\Handler\Store\GetCategoryHandler;
    use App\Handler\Store\GetProductHandler;
    use App\Handler\Store\PostProductHandler;
    use App\Handler\Store\PostCategoryHandler;
    use App\Handler\Store\PutProductHandler;
    use App\Handler\Store\PutCategoryHandler;
    use App\Handler\Store\DeleteProductHandler;
    use App\Handler\Store\DeleteCategoryHandler;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\ORM\EntityManagerInterface;
    use Ramsey\Uuid\Uuid;
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    #$uri = parse_url($_SERVER['REQUEST_URI']);
    #print_r($uri);
    class StoreController extends AbstractController
    {
        protected EntityManagerInterface $em; 
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->em = $entityManager;
        }
       #[Route('/store/category/{name}', methods: ['GET'])]
       public function goCategory(string $name=''): Response
       {
           $response;
           $handler = new GetCategoryHandler($this->em);
           if($name){
               $response=new Response($handler->getCategory($name));
           }
           else{
               $response=new Response($handler->getAllCategories());
           }
           
           return $response;
       }
       #[Route('/store/product/{uuid}', methods: ['GET'])]
       public function goProduct(string $uuid) {
           $handler = new GetProductHandler($this->em);
           $response=new Response($handler->getProduct($uuid));
           return $response;
       }
       #[Route('/store/category/{name}/products', methods: ['GET'])]
       public function goProducts(Request $request,string $name) {
           $response=[];
           $qb = $this->em->createQueryBuilder();
           $data = json_decode($request->getContent(), true);
         
           $filters = $data['filters'] ?? null;
           $absolute_similarity = $data['absolute_similarity'] ?? null;
           $sort = $data["sort"] ?? null;
           $category_handler = new GetCategoryHandler($this->em);
           $categories= json_decode($category_handler->getCategory($name));
           
           $products = $category_handler->findProductsInCategories($qb, $categories,
                    $absolute_similarity, $filters, $sort);
            foreach ($products as $product){
                array_push($response,["uuid"=>$product['product']->getUuid(),
                              "info"=>$product['product']->getInfo(),
                              "category"=>$product['product']->getCategory()->getUuid(),
                              "price"=> $product['product']->getPrice()->getPrice(),
                              "count"=>$product['itemcount']]);
            }
           return new JsonResponse($response);
       }
    #[Route('/store/product', methods: ['POST'])]
    public function addProduct(Request $request): JsonResponse
    {   
        $handler = new PostProductHandler($this->em);
        $data = json_decode($request->getContent(), true);
         
        $info = $data['info'] ?? null;
        $category_name = $data['category_name'] ?? null;
        $handler->postProduct($info,$category_name);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'info' => $info,
            'category_name' => $category_name
        ]);
    }
    #[Route('/store/product', methods: ['PUT'])]
    public function updateProduct(Request $request): JsonResponse
    {   
        $handler = new PutProductHandler($this->em);
        $data = json_decode($request->getContent(), true);
        $uuid = Uuid::fromString($data["uuid"]);
        $info = $data['info'] ?? null;
        $category_name = $data['category_name'] ?? null;
        $handler->putProduct($uuid,$info,$category_name);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'info' => $info,
            'category_name' => $category_name
        ]);
    }
    #[Route('/store/product', methods: ['DELETE'])]
    public function removeProduct(Request $request): JsonResponse
    {   
        $handler = new DeleteProductHandler($this->em);
        $data = json_decode($request->getContent(), true);
        $uuid = Uuid::fromString($data["uuid"]);
        $handler->deleteProduct($uuid);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'uuid' => $uuid
        ]);
    }
    #[Route('/store/category', methods: ['POST'])]
    public function addCategory(Request $request): JsonResponse
    {   
        $handler = new PostCategoryHandler($this->em);
        $data = json_decode($request->getContent(), true);
         
        $parent_name = $data['parent_name'] ?? null;
        $category_name = $data['category_name'] ?? null;
        $prod_characteristics = $data['prod_characteristics'] ?? null;
        $handler->postCategory($parent_name,$category_name,$prod_characteristics);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'parent_name' => $parent_name,
            'category_name' => $category_name,
            'prod_characteristics'=> $prod_characteristics
        ]);
    }
    #[Route('/store/category', methods: ['PUT'])]
    public function updateCategory(Request $request): JsonResponse
    {   
        $handler = new PutCategoryHandler($this->em);
        $data = json_decode($request->getContent(), true);
        $uuid = Uuid::fromString($data["uuid"]);
        $parent_name = $data['parent_name'] ?? null;
        $category_name = $data['category_name'] ?? null;
        $prod_characteristics = $data['prod_characteristics'] ?? null;
        $handler->putCategory($uuid,$parent_name,$category_name,$prod_characteristics);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'uuis' => $uuid,
            'category_name' => $category_name,
            'parent_name' => $parent_name,
            "prod_characteristics" =>$prod_characteristics
        ]);
    }
    #[Route('/store/category', methods: ['DELETE'])]
    public function removeCategory(Request $request): JsonResponse
    {   
        $handler = new DeleteCategoryHandler($this->em);
        $data = json_decode($request->getContent(), true);
        $uuid = Uuid::fromString($data["uuid"]);
        $handler->deleteCategory($uuid);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'uuid' => $uuid
        ]);
    }
    }
   
    #print_r(basename(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)))
?>
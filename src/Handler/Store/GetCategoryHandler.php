<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Orx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\Product;
class GetCategoryHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllCategories()
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $response = [];
        foreach ($categories as $category){
            array_push($response,[
                    "uuid"=>$category->getUuid(),
                    "parent_uuid"=>$category->getParentUuid(),
                    "name"=>$category->getCategoryName(),
                    "characteristics"=>$category->getCharacteristics()
            ]);
        }
        return json_encode($response);
    }
    public function getCategory(string $name) {
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(
                ["category_name"=>$name]);
        if ($category){
            $related_categories = $this->findChildren($this->entityManager, $category->getUuid());
            array_push($related_categories,[
                    "uuid"=>$category->getUuid(),
                    "parent_uuid"=>$category->getParentUuid(),
                    "name"=>$category->getCategoryName(),
                    "characteristics"=>$category->getCharacteristics()
                ]);
            return json_encode($related_categories);
            
        }
        else {
            return json_encode(["error"=>404]);
        }
        
    }
    public function findChildren($em,$parent_uuid){
        $all_children = [];
        $children = $em->getRepository(Category::class)->findBy(['parent_uuid' => $parent_uuid]);
        
        if ($children){
            foreach ($children as &$child) {
                array_push($all_children, [
                    "uuid"=>$child->getUuid(),
                    "parent_uuid"=>$child->getParentUuid(),
                    "name"=>$child->getCategoryName(),
                    "characteristics"=>$child->getCharacteristics()
                ]);
                
                $children_descendant = $this->findChildren($em, $child->getUuid());
                $all_children=array_merge($all_children,$children_descendant);
                }
            return $all_children;
        }
        else {
            return [];
        }
    }
    public function findProductsInCategories($qb, ?array $categories,
             ?bool $absolute_similarity,
             ?array $filters, ?array $sort) {
        
        $categoryUuids = array_map(fn($category) => $category->uuid, $categories);
           $qb->select('p')
              ->from(Product::class, 'p')
              ->join('p.category', 'c')
              ->andWhere($qb->expr()->in('c.uuid', ':categoryUuids'))
              ->setParameter('categoryUuids', $categoryUuids);
            
            
            if($absolute_similarity and $filters){
                foreach ($filters as $filter=>$value){
                    $dqlExpr = "JSON_GET_FIELD_AS_TEXT(JSON_GET_FIELD(p.info,'characteristics'),:filter)"
                            . " = :value_$filter";
                    $qb->andWhere($dqlExpr)
                       ->setParameter("filter",$filter)
                       ->setParameter("value_$filter", $value);
                }
            }
            elseif ($filters) {
                $orx = new Orx();
                foreach ($filters as $filter=>$value){
                    
                    $dqlExpr = "JSON_GET_FIELD_AS_TEXT(JSON_GET_FIELD(p.info,'characteristics'),:filter)"
                            . " = :value_$filter";
                    $orx->add($dqlExpr);
                    $qb->setParameter("filter",$filter)
                       ->setParameter("value_$filter", $value);
                }
                $qb->andWhere($orx);
            }
            foreach ($sort as $type=>$order){
                if ($type == "price"){
                    $qb->innerJoin('p.price', 'pr')
                       ->addOrderBy("pr.price",$order);
                }
                else{
                    $path = 'p.' . $type;
                    $exploded_path = explode(".", $path);
                    if (count($exploded_path)===2){
                        $qb->addOrderBy($path,$order);
                    }       
                    else{
                        $target = end($exploded_path);
                        $path= str_replace("." . $target, "", $path);
                        print_r($path . "/" . $target);
                        $qb->addOrderBy("JSON_GET_FIELD_AS_TEXT($path,'$target')",$order);
                                
                    }
                }
            }
     return $qb->getQuery()->getResult();
    }
}
?>
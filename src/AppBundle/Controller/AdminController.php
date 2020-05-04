<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    const UPLOAD_PATH = '/web/uploads/photos/';
    /**
     * @Route("/admin/listProducts")
     */
    public function listProductsAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Product');

        $products = $repo->findAll();

        return $this->render('AppBundle:Admin:list_products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/addProduct",methods={"GET"})
     */
    public function addProductAction()
    {
        $em = $this->getDoctrine();

        $categories = $em->getRepository('AppBundle:Category')->findAll();


        return $this->render('AppBundle:Admin:add_product.html.twig',
            [
                'categories'=>$categories,
                'action'=>'add'
            ]
        );
    }

    /**
     * @Route("/admin/editProduct/{id}",methods={"GET"})
     */
    public function editProductAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $prodRepo = $em->getRepository('AppBundle:Product');

        $em = $this->getDoctrine();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $product = $prodRepo->find($id);
        return $this->render('AppBundle:Admin:add_product.html.twig', array(
            'product'=>$product,
            'categories'=>$categories,
            'action'=>'edit'
        ));
    }

    /**
     * @Route("/admin/addphoto/{id}",methods={"POST"})
     */
    public function addPhotoAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->find($id);

        $photo = $request->files->get('photo');

        $realPath =  $photo->getRealPath();
        $newName = uniqid().".".$photo->getClientOriginalExtension();

        $root = $this->get('kernel')->getProjectDir();


        $path = $root."/".self::UPLOAD_PATH.$newName;

        if(move_uploaded_file($realPath,$path)) {
            $photo = new Photo();
            $photo->setPath($newName);
            $photo->setProduct($product);
            $photo->setIsMain(0);
            $em->persist($photo);
            $em->flush();
        }

        return $this->redirectToRoute('app_admin_managephotos',['id'=>$id]);
    }
    /**
     * @Route("/admin/managephotos/{id}",methods={"GET"})
     */
    public function managePhotosAction($id) {
        $em = $this->getDoctrine()->getManager();
        $prodRepo = $em->getRepository('AppBundle:Product');


        $product = $prodRepo->find($id);

        $photos = $product->getPhotos();

        return $this->render('AppBundle:Admin:manage_photos.html.twig',[
            'product'=>$product,
            'photos'=>$photos
        ]);
    }

    /**
     * @Route("/admin/saveProduct/{id}",methods={"POST"})
     */
    public function saveProductAction(Request $request, $id = null)
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $catId = $request->request->get('categoryId');
        $em = $this->getDoctrine()->getManager();

        $catRepo = $em->getRepository('AppBundle:Category');

        $errors = [];

        if(trim($name) == '') {
            $errors[] = 'wrong name';
        }
        if(trim($description) == '') {
            $errors[] = 'wrong desc.';
        }
        if(!is_numeric($price)) {
            $errors[] = 'wrong price';
        }
        if(!is_numeric($catId)) {
            $errors[] = 'category not selected';
        }


        if(empty($errors)) {
            if(is_numeric($id)) {
                $product = $em->getRepository('AppBundle:Product')
                    ->find($id);
            } else {
                $product = new Product();
            }
            $cat = $catRepo->find($catId);
            $product->setCategory($cat);
            $product->setName($name);
            $product->setPrice($price);
            $product->setDescription($description);
            $product->setPath("/".strtolower(str_replace(' ',"-",$name)));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_admin_listproducts');
        } else {
            $categories = $catRepo->findAll();
            return $this->render('AppBundle:Admin:add_product.html.twig', [
                'categories'=>$categories,
                'errors'=>$errors,
                'product' => $request->request->all()
            ]);
        }
    }

    /**
     * @Route("/admin/deleteProduct/{id}",methods={"GET"})
     */
    public function deleteProductAction($id = null)
    {

        $em = $this->getDoctrine()->getManager();
        $prodRepo = $em->getRepository('AppBundle:Product');
        $em->remove($prodRepo->findOneById($id));
        $em->flush();

        return $this->redirectToRoute('app_admin_listproducts');

    }

    /**
     * @Route("/admin/setmain/{id}",methods={"POST"})
     */
    public function setMainAction(Request $request, $id) {
        $idPhoto = $request->request->get('isMain');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Photo');
        $prodRepo = $em->getRepository('AppBundle:Product');
        $photo = $repo->find($idPhoto);
        $photos = $prodRepo->find($id)->getPhotos();
        foreach ($photos as $crt) {
            $crt->setIsMain(0);
            $em->persist($crt);
        }
        $photo->setIsMain(1);
        $em->persist($photo);
        $em->flush();
        return $this->redirectToRoute('app_admin_managephotos',['id'=>$id]);
    }

    /**
     * @Route("/admin/deletePhoto/{id}",methods={"GET"})
     */
    public function deletePhotoAction($id = null)
    {

        $em = $this->getDoctrine()->getManager();
        $prodRepo = $em->getRepository('AppBundle:Photo');
        $photo = $prodRepo->findOneById($id);
        $productId = $photo->getProductId();

        $root = $this->get('kernel')->getProjectDir();
        $path = $root."/".self::UPLOAD_PATH.$photo->getPath();

        if(unlink($path)){
            $em->remove($photo);
            $em->flush();
        }

        return $this->redirectToRoute('app_admin_managephotos',['id'=>$productId]);

    }

}
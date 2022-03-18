<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Mobile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        //Mặc định, xem tất cả sản phẩm 
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); //Lấy danh sách hãng mobile, mục đích: hiển thị trên thanh điều hướng 
        $mobiles = $this->getDoctrine()->getRepository(Mobile::class)->findAll(); //Lấy danh sách sản phẩm
        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'mobiles' => $mobiles,
        ]);
    }

    /**
     * @Route("/brand/{id}", name="viewByBrand")
     */
    public function viewByBrand($id): Response
    {
        //Xem sản phẩm theo hãng (brand)
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); //Lấy danh sách hãng mobile, mục đích: hiển thị trên thanh điều hướng 
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id); //Tìm hãng theo id
        //Nếu không tìm được thì điều hướng về trang chủ
        if ($brand == null) return $this->redirectToRoute("home");

        $mobiles = $brand->getMobiles(); //lấy danh sách sản phẩm theo hãng

        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'mobiles' => $mobiles,
        ]);
    }
}

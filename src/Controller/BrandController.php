<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Mobile;
use App\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    /**
     * @Route("/manager/brand/create", name="createBrand")
     */
    public function create(Request $request): Response
    {
        $brand = new Brand();
        //Tạo form dựa trên form type (xem App\Form\BrandType)
        $form = $this->createForm(BrandType::class, $brand); 
        $form->handleRequest($request);
        
        //Nếu submit form -> xử lý và quay lại trang quản lý
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            //Kiểm tra xem user có upload ảnh không
            $image = $form->get('ImageUpload')->getData();
            //Nếu != null tức là có
            if ($image != null) {
                //Tạo một cái tên riêng biệt
                $fileName = md5(uniqid());
                //Lấy Extension của file (.jpg, .png gì đó)
                $fileExtension = $image->guessExtension();
                //Tên file mới
                $imageName = $fileName . '.' . $fileExtension;
                //Chuyển file vào folder muốn lưu, cài đặt folder trong config/services.yaml
                try {
                    $image->move(
                        $this->getParameter('images_directory'), $imageName
                    );
                } catch (FileException $e) {
                    //Nếu lỗi thì return lỗi
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                //Set tên file ảnh cho $brand (logo)
                $brand->setLogo($imageName);
            }
            //Lưu (tạm thời)
            $manager->persist($brand);
            //Lưu vào database
            $manager->flush();
            //Thông báo (không quan trọng lắm)
            $this->addFlash("Info", "Create brand succeed!");
            //Điều hướng về trang quản lý (xem ViewManagerController)
            return $this->redirectToRoute("brandManager"); 
        }

        //Nếu truy cập -> render trang create
        return $this->render('brand/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/brand/update/{id}", name="updateBrand")
     */
    public function update(Request $request, $id): Response
    {
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        //Nếu kết quả là null (không tìm thấy) thì điều hướng về trang quản lý
        if ($brand == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("brandManager");   
        }
        //Tạo form dựa trên form type (xem App\Form\BrandType)
        $form = $this->createForm(BrandType::class, $brand); 
        $form->handleRequest($request);
        
        //Nếu submit form -> xử lý và quay lại trang quản lý
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            //Kiểm tra xem user có upload ảnh không
            $image = $form->get('ImageUpload')->getData();
            //Nếu != null tức là có
            if ($image != null) {
                //Tạo một cái tên riêng biệt
                $fileName = md5(uniqid());
                //Lấy Extension của file (.jpg, .png gì đó)
                $fileExtension = $image->guessExtension();
                //Tên file mới
                $imageName = $fileName . '.' . $fileExtension;
                //Chuyển file vào folder muốn lưu, cài đặt folder trong config/services.yaml
                try {
                    $image->move(
                        $this->getParameter('images_directory'), $imageName
                    );
                } catch (FileException $e) {
                    //Nếu lỗi thì return lỗi
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                //Set tên file ảnh cho $brand (logo)
                $brand->setLogo($imageName);
            }
            //Lưu (tạm thời)
            $manager->persist($brand);
            //Lưu vào database
            $manager->flush();
            //Thông báo (không quan trọng lắm)
            $this->addFlash("Info", "Create brand succeed!");
            //Điều hướng về trang quản lý (xem ViewManagerController)
            return $this->redirectToRoute("brandManager"); 
        }

        //Nếu truy cập -> render trang update
        return $this->render('brand/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/brand/delete/{id}", name="deleteBrand")
     */
    public function delete(Request $request, $id): Response
    {
        //Tìm theo id
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        
        //Nếu kết quả là null (không tìm thấy) thì điều hướng về trang quản lý
        if ($brand == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("brandManager");   
        }
        $manager = $this->getDoctrine()->getManager();
        //Xóa 
        $manager->remove($brand);
        //Lưu thay đổi vào database
        $manager->flush();
        $this->addFlash("Info", "Delete brand succeed !");
        //Điều hướng về trang quản lý
        return $this->redirectToRoute("brandManager"); 
    }
}


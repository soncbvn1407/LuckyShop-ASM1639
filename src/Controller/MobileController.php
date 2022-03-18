<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Mobile;
use App\Form\MobileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MobileController extends AbstractController
{
    /**
     * @Route("/manager/mobile/create", name="createMobile")
     */
    public function create(Request $request): Response
    {
        $mobile = new Mobile();
        //Tạo form dựa trên form type (xem App\Form\MobileType)
        $form = $this->createForm(MobileType::class, $mobile); 
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
                //Set tên file ảnh cho $mobile 
                $mobile->setImage($imageName);
            }
            //Lưu (tạm thời)
            $mobile->setTotal($mobile->getAmount() * $mobile->getPrice());
            $manager->persist($mobile);
            //Lưu vào database
            $manager->flush();
            //Thông báo (không quan trọng lắm)
            $this->addFlash("Info", "Create mobile succeed!");
            //Điều hướng về trang quản lý (xem ViewManagerController)
            return $this->redirectToRoute("mobileManager"); 
        }

        //Nếu truy cập -> render trang create
        return $this->render('mobile/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/mobile/update/{id}", name="updateMobile")
     */
    public function updateMobile(Request $request, $id): Response
    {
        $mobile = $this->getDoctrine()->getRepository(Mobile::class)->find($id);
        //Nếu kết quả là null (không tìm thấy) thì điều hướng về trang quản lý
        if ($mobile == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("mobileManager");   
        }
        //Tạo form dựa trên form type (xem App\Form\MobileType)
        $form = $this->createForm(MobileType::class, $mobile); 
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
                //Set tên file ảnh cho $mobile 
                $mobile->setImage($imageName);
            }
            //Lưu (tạm thời)
            $mobile->setTotal($mobile->getAmount() * $mobile->getPrice());


            $manager->persist($mobile);
            //Lưu vào database
            $manager->flush();
            //Thông báo (không quan trọng lắm)
            $this->addFlash("Info", "Update mobile succeed!");
            //Điều hướng về trang quản lý (xem ViewManagerController)
            return $this->redirectToRoute("mobileManager"); 
        }

        //Nếu truy cập -> render trang update
        return $this->render('mobile/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/mobile/delete/{id}", name="deleteMobile")
     */
    public function deleteMobile(Request $request, $id): Response
    {
        $mobile = $this->getDoctrine()->getRepository(Mobile::class)->find($id);
        //Nếu kết quả là null (không tìm thấy) thì điều hướng về trang quản lý
        if ($mobile == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("mobileManager");   
        }
        $manager = $this->getDoctrine()->getManager();
        //Xóa 
        $manager->remove($mobile);
        //Lưu thay đổi vào database
        $manager->flush();
        $this->addFlash("Info", "Delete mobile succeed !");
        //Điều hướng về trang quản lý
        return $this->redirectToRoute("mobileManager"); 
    }
}

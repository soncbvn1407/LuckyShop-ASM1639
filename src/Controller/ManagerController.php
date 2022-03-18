<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Mobile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    /**
     * @Route("/manager/mobile", name="mobileManager")
     */
    public function mobileManager(): Response
    {
        $mobiles = $this->getDoctrine()->getRepository(Mobile::class)->findAll(); 
        $count = count($mobiles);
        return $this->render('manager/mobile.html.twig', [
            'mobiles' => $mobiles,
            'count' => $count
        ]);
    }

    /**
     * @Route("/manager/brand", name="brandManager")
     */
    public function brandManager(): Response
    {
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        return $this->render('manager/brand.html.twig', [
            'brands' => $brands,
        ]);
    }
}

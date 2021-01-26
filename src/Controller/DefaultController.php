<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\CenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em,UserPasswordEncoderInterface $encoder): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/mapinfo", name="mapinfo")
     */
    public function map(EntityManagerInterface $em, CenterRepository $centerRepository): Response
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $agencies = $centerRepository->findAll();

        $datas = [];

        foreach ($agencies as $key => $agency) {
            $datas[$key]['id'] = $agency->getId();
            $datas[$key]['city'] = $agency->getCity();
            $datas[$key]['lat'] = $agency->getLat();
            $datas[$key]['lon'] = $agency->getLon();
            $datas[$key]['adress'] = $agency->getAdress();

        }

        return new JsonResponse (['agences' => $datas]);
    }

}
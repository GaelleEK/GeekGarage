<?php

namespace App\Controller\Admin;


use App\Entity\Center;
use App\Entity\Contact;
use App\Form\CenterFormType;
use App\Repository\CenterRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
   /**
    * @Route("/admin/home", name="admin_home")
    */
   public function index(CenterRepository $centerRepository): Response
   {

       return $this->render('admin/index.html.twig', [
            'centers'=> $centerRepository->findAll()
           ]);
   }

    /**
     * @Route("/admin/create", name="admin_create")
     */
   public function create(Request $request, EntityManagerInterface $em): Response
   {
       $center = new Center();
       $form = $this->createForm(CenterFormType::class, $center);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid())
       {
           $em->persist($center);
           $em->flush();
           return $this->redirectToRoute('admin_home');
       }

       return $this->render('admin/create.html.twig', ['form'=> $form->createView()]);

   }

    /**
     *
     * @Route("admin/update/{id<[0-9]+>}", name="admin_update")
     */
    public function update(Request $request, EntityManagerInterface $em, Center $center): Response
    {
       $form = $this->createForm(CenterFormType::class, $center);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid())
       {
           $em->flush();
           return $this->redirectToRoute('admin_home');
       }
       return $this->render('admin/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("admin/delete/{id<[0-9]+>}", name="admin_delete")
     */
    public function delete(EntityManagerInterface $em, Center $center)
    {
       $em->remove($center);
       $em->flush();
       return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("admin/date", name="admin_date")
     */
    public function dateIndex(ContactRepository $contactRepository, CenterRepository $centerRepository): Response
    {
        $contacts =  $contactRepository->findAll();


        return $this->render('admin/date.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("admin/date/delete/{id<[0-9]+>}", name="admin_date_delete")
     */
    public function deleteDate(EntityManagerInterface $em, Contact $contact)
    {
        $em->remove($contact);
        $em->flush();
        return $this->redirectToRoute('admin/date.html.twig');
    }
}
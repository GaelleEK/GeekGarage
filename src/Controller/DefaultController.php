<?php


namespace App\Controller;


use App\Entity\Center;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\CenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(CenterRepository $centerRepository, EntityManagerInterface $em): Response
    {
        $qb = $em->createQueryBuilder();
        $qb->select('count(contact.id)');
        $qb->from('App\Entity\Contact','contact');
        $count = $qb->getQuery()->getSingleScalarResult();

        return $this->render('home.html.twig', [
            'centers'=> $centerRepository->findAll(),
            'count' => $count
        ]);
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
            $datas[$key]['address'] = $agency->getAddress();

        }

        return new JsonResponse (['agences' => $datas]);
    }

    /**
     * @Route("home/contact/{id<[0-9]+>}", name="home_contact")
     */
    public function contactCenter(Request $request, EntityManagerInterface $em, Center $center, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // création email pour envoi
            $to = $form->get('mail')->getData();
            $email = (new TemplatedEmail())
                ->from($to)
                ->to("mymail@mail.com")
                ->subject('Demande de dépannage centre de '. $center->getCity())
                ->htmlTemplate('emails/contact_depannage.html.twig')
                ->context([

                    'first_name' => $form->get('first_name')->getData(),
                    'name' => $form->get('name')->getData(),
                    'tel' => $form->get('tel')->getData(),
                    'mail' => $form->get('mail')->getData(),
                    'message' => $form->get('message')->getData(),
                ]);
            $mailer->send($email);

            // set de l entité contact pour entrer en bdd
            $contact->setCenter($center->getCity());
            $contact->setFirstName($form->get('first_name')->getData());
            $contact->setName($form->get('name')->getData());
            $contact->setMail($form->get('mail')->getData());
            $contact->setTel($form->get('tel')->getData());
            $contact->setMessage($form->get('message')->getData());
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'mail envoyé');
            return $this->redirectToRoute('home');

        }
            return $this->render('contact.html.twig', [
                'form' => $form->createView(),
                'center' => $center]);
    }
}
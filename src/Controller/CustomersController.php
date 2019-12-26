<?php
namespace App\Controller;

use App\Entity\Customers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomersController extends AbstractController
{

    /**
     *
     * @Route("/customers", name="customers")
     */
    public function index()
    {
        return $this->render('customers/index.html.twig', [
            'controller_name' => 'CustomersController'
        ]);
    }

    /**
     *
     * @Route("customers/add", name="add_customers")
     */
    public function add()
    {
        $event = new Customers();
        $event->setName('Gopi M');
        $event->setEmail('gopi.m@gmail.com');
        $event->setPassword('welcome');
        $event->setDateOfAdded(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->render('customers/index.html.twig', [
            'customer_id' => $event->getId()
        ]);
    }
}

<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogOutController extends AbstractController
{

    /**
     *
     * @Route("/log/out", name="log_out")
     */
    public function index()
    {
        $this->get('session')->remove('user_id');
        $this->get('session')->remove('user_email');
        return $this->render('log_out/index.html.twig', [
            'controller_name' => 'LogOutController'
        ]);
    }
}

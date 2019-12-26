<?php
namespace App\Controller;

use App\Entity\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AddTaskController extends AbstractController
{

    /**
     *
     * @Route("/add/task", name="add_task")
     */
    public function index()
    {
        return $this->render('add_task/index.html.twig', [
            'controller_name' => 'AddTaskController'
        ]);
    }

    /**
     *
     * @Route("/insert/record", name="insert_record")
     */
    public function insertRecord(Request $request)
    {
        $data = $request->request->all();
        $task = new Tasks();
        $task->setTaskName($data['task']);
        $task->setTaskStatus('incomplete');
        $task->setDateOfAdded(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();
        if ($task->getId()) {
            $this->addFlash('notice', 'Your changes were saved!');
        }

        return $this->redirectToRoute('add_task');
    }

}

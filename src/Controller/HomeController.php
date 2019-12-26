<?php
namespace App\Controller;

use App\Entity\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    /**
     *
     * @Route("/", name="home")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT * FROM tasks;';

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $tasks = $statement->fetchAll();
        return $this->render('home/index.html.twig', [
            'data' => $tasks
        ]);
    }

    /**
     *
     * @Route("/delete/task/{id}", name="delete_task")
     */
    public function deleteTask($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Tasks::class)->findOneBy([
            'id' => $id
        ]);
        if (! $task) {
            $this->addFlash('warning', 'Given task not found!');
            return $this->redirectToRoute('home');
        }
        $em->remove($task);
        $em->flush();
        if ($em) {
            $this->addFlash('notice', 'Your task deleted successfully!');
        }
        return $this->redirectToRoute('home');
    }

    /**
     *
     * @Route("/task/status/{id}", name="change_task_status")
     */
    public function changeTaskStatus($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Tasks::class)->find([
            'id' => $id
        ]);

        if (! $task) {
            $this->addFlash('warning', 'Given task not found!');
            return $this->redirectToRoute('home');
        }

        if ($task->getTaskStatus() == 'complete') {
            $task->setTaskStatus('incomplete');
        } else {
            $task->setTaskStatus('complete');
        }
        $em->flush();
        if ($em) {
            $this->addFlash('notice', 'Your task status changed successfully!');
        }
        return $this->redirectToRoute('home');
    }

    /**
     *
     * @Route("/task/update/{id}", name="update_task")
     */
    public function getUpdateTask($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Tasks::class)->find([
            'id' => $id
        ]);

        if (! $task) {
            $this->addFlash('warning', 'Given task not found!');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('add_task/index.html.twig', [
            'data' => $task,
            'update' => 'yes'
        ]);
        return $this->redirectToRoute('home');
    }

    /**
     *
     * @Route("/task/update_task/", name="update_task_post")
     */
    public function postUpdateTask(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Tasks::class)->find([
            'id' => $data['id']
        ]);

        if (! $task) {
            $this->addFlash('warning', 'Given task not found!');
            return $this->redirectToRoute('home');
        }
        $task->setTaskName($data['task_name']);
        $em->flush();
        if ($em) {
            $this->addFlash('notice', 'Your task updated successfully!');
        }
        return $this->redirectToRoute('home');
    }
}

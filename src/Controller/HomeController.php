<?php
namespace App\Controller;

use App\Entity\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{

    /**
     *
     * @var int
     */
    private $user_id;

    /**
     *
     * @param SessionInterface $se
     */
    public function __construct(SessionInterface $se)
    {
        $this->user_id = $se->get('user_id');
    }

    /**
     *
     * @Route("/", name="home")
     */
    public function index()
    {
        if (! $this->user_id) {
            return $this->redirectToRoute('log_in');
        }
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT * FROM tasks WHERE created_by =' . $this->user_id;

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
    public function deleteTask($id, ValidatorInterface $validator)
    {
        // validation process
        $data = array(
            'id' => $id
        );
        $constraints = new Assert\Collection([
            'id' => [
                new Assert\NotBlank()
            ]
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }

            return $this->redirectToRoute('home');
        }

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
    public function changeTaskStatus($id, ValidatorInterface $validator)
    {
        // validation process
        $data = array(
            'id' => $id
        );
        $constraints = new Assert\Collection([
            'id' => [
                new Assert\NotBlank()
            ]
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }

            return $this->redirectToRoute('home');
        }

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
    public function postUpdateTask(Request $request, ValidatorInterface $validator)
    {
        $data = $request->request->all();
        // validation process
        $constraints = new Assert\Collection([
            'task_name' => [
                new Assert\NotBlank()
            ],
            'task_status' => [
                new Assert\NotBlank()
            ],
            'id' => [
                new Assert\NotBlank()
            ]
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }

            return $this->redirectToRoute('home');
        }

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

    /**
     *
     * @Route("/search/task", name="search_task")
     */
    public function searchTask(Request $request, ValidatorInterface $validator)
    {
        $data = $request->request->all();
        $task_name = $request->get('task_name');
        // validation process
        $constraints = new Assert\Collection([
            'task_name' => [
                new Assert\NotBlank()
            ]
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }

            return $this->redirectToRoute('home');
        }

        if (! $this->user_id) {
            return $this->redirectToRoute('log_in');
        }
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT * FROM tasks WHERE created_by =' . "'$this->user_id'" . ' AND task_name like ' . "'%$task_name%'";

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $tasks = $statement->fetchAll();
        return $this->render('home/index.html.twig', [
            'data' => $tasks
        ]);
    }
}

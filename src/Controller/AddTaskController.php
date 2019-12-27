<?php
namespace App\Controller;

use App\Entity\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use App\Form\AddTaskType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddTaskController extends AbstractController
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
     * @Route("/add/task", name="add_task")
     */
    public function index()
    {
        if (! $this->user_id) {
            return $this->redirectToRoute('log_in');
        }
        if (! $this->user_id) {
            return $this->redirectToRoute('log_in');
        }

        return $this->render('add_task/index.html.twig', [
            'controller_name' => 'AddTaskController'
        ]);
    }

    /**
     *
     * @Route("/insert/record", name="insert_record")
     */
    public function insertRecord(Request $request, ValidatorInterface $validator)
    {
        // validation process
        $data = $request->request->all();
        $constraints = new Assert\Collection([
            'task_name' => [
                new Assert\Length([
                    'min' => 2
                ]),
                new Assert\NotBlank()
            ]
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }

            return $this->redirectToRoute('add_task');
        }

        // Entity process
        $task = new Tasks();
        $task->setTaskName($data['task_name']);
        $task->setTaskStatus('incomplete');
        $task->setCreatedBy($this->get('session')
            ->get('user_id'));
        $task->setDateOfAdded(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();
        if ($task->getId()) {
            $this->addFlash('notice', 'Your task were saved!');
        }

        return $this->redirectToRoute('add_task');
    }
}

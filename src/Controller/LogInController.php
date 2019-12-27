<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LogInController extends AbstractController
{

    /**
     *
     * @Route("/log/in", name="log_in")
     */
    public function index()
    {
        return $this->render('log_in/index.html.twig', [
            'controller_name' => 'LogInController'
        ]);
    }

    /**
     *
     * @Route("/log/in/post", name="log_in_post")
     */
    public function loginPost(Request $request, ValidatorInterface $validator)
    {
        $data = $request->request->all();
        // validation process
        $constraints = new Assert\Collection([
            'email' => [
                new Assert\Length([
                    'min' => 2
                ]),
                new Assert\NotBlank([
                    'message' => 'Email could not be blank'
                ]),
                new Assert\Email([
                    'message' => 'This is not the corect email format'
                ])
            ],
            'password' => [
                new Assert\NotBlank([
                    'message' => 'Password should not be blank'
                ])
            ]
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }
            return $this->redirectToRoute('log_in');
        }
        $repository = $this->getDoctrine()->getRepository(Users::class);
        // or find by name and price
        $user = $repository->findOneBy([
            'user_email' => $data['email'],
            'user_password' => $data['password']
        ]);
        if (! $user) {
            $this->addFlash('warning', 'Email / Password is incorrect!');
            return $this->redirectToRoute('log_in');
        }

        if ($user->getId()) {
            $this->addFlash('notice', 'Your loged in successfully!');
            $this->get('session')->set('user_id', $user->getId());
            $this->get('session')->set('user_email', $user->getUserEmail());

            return $this->redirectToRoute('home');
        }

        return $this->render('log_in/index.html.twig', [
            'controller_name' => 'LogInController'
        ]);
    }
}

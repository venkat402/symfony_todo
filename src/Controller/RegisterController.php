<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterController extends AbstractController
{

    /**
     *
     * @Route("/register", name="register")
     */
    public function index()
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController'
        ]);
    }

    /**
     *
     * @Route("/register_post", name="register_post")
     */
    public function registerRequest(Request $request, ValidatorInterface $validator)
    {
        $data = $request->request->all();
        // validation process
        $constraints = new Assert\Collection([
            'email' => [
                new Assert\Length([
                    'max' => 255
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
                    'message' => 'Password would not be blank'
                ])
            ],
            'tandc' => new Assert\NotBlank([
                'message' => 'Temrs and coditions should be checked'
            ])
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->addFlash('warning', $violation->getMessage());
            }
            return $this->redirectToRoute('register');
        }
        if (! isset($data['tandc'])) {
            $this->addFlash('warning', 'You need to accept terms and conditions !');
            return $this->redirectToRoute('register');
        }

        $user = new Users();
        $user->setUserEmail($data['email']);
        $user->setUserPassword($data['password']);
        $user->setDateOfAdded(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        if ($user->getId()) {
            $this->addFlash('notice', 'Your Registration is success! Login here to continue');
        }

        return $this->redirectToRoute('log_in');
    }
}

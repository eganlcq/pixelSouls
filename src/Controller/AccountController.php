<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error      = $utils->getLastAuthenticationError();
        $username   = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError'  =>  $error !== null,
            'username'  =>  $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout() {}

    /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            if(!is_null($user->getImage())) {

                $file = $user->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $user->setImage($fileName);
            }
            
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Your account has been successfully created");

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [

            'form'  =>  $form->createView()
        ]);
    }
}

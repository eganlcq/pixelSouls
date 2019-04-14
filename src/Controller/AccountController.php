<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\RoleRepository;
use App\Repository\FightRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, RoleRepository $repo) {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $role = $repo->findOneByName('ROLE_USER');
            $user->addCurrentRole($role);
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

    /**
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, ObjectManager $manager) {

        $user = $this->getuser();
        $img = $user->getImage();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!is_null($user->getImage())) {

                $file = $user->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $user->setImage($fileName);
            }
            else {

                $user->setImage($img);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Your profile has been successfully updated !");
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager) {

        $user = $this->getUser();
        $passwordUpdate = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

                $form->get('oldPassword')->addError(new FormError("Incorrect password given"));
            }
            else {

                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Your password has been successfully updated !");

                return $this->redirectToRoute('game');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount(FightRepository $repo) {

        $user = $this->getUser();
        $fights = $repo->findFightsByUser($user->getId());

        foreach($fights as $fight) {

            foreach($user->getFighters() as $fighter) {

                if($fighter == $fight->getOpponent()) {

                    $tempFighter = $fight->getFighter();
                    $fight->setFighter($fight->getOpponent());
                    $fight->setOpponent($tempFighter);
                    $fight->setIsWon(!$fight->getIsWon());
                }
            }
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'fights' => $fights
        ]);
    }
}

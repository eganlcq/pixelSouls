<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\PatchNote;
use App\Form\PatchNoteType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatchController extends AbstractController
{
    /**
     * @Route("/admin/patch/create", name="patch_create")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $patch = new PatchNote();
        $form = $this->createForm(PatchNoteType::class, $patch);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            foreach($patch->getNotes() as $note) {

                $note->setPatchNote($patch);
                $manager->persist($note);
            }
            $manager->persist($patch);
            $manager->flush();

            $this->addFlash('success', "The patch note has been created");
            return $this->redirectToRoute("admin_dashboard");
        }

        return $this->render('admin/patch/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/patch/{id}", name="patch_show")
     */
    public function show(PatchNote $patch) {

        return $this->render('patch/show.html.twig', [
            'patch' => $patch
        ]);
    }
}

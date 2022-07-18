<?php

namespace App\Controller;

use App\Form\FaccionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FaccionController extends AbstractController{
    #[Route("/newFaccion", name: "newFaccion")]
    public function addFaccion(EntityManagerInterface $doctrine, Request $request){
        $form = $this->createForm(FaccionFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $faccion = $form->getData();
            $doctrine->persist($faccion);
            $doctrine->flush();
            $this->addFlash("Success", "Faccion insertada correctamente");
            return $this->redirectToRoute("newFaccion");
        }
        return $this->renderForm("ow/formFaccion.html.twig", ["formFaccion" => $form]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Characters;
use App\Form\CharactersFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController{
    
    #[Route("/", name: "home")]
    public function home(){
        return $this->render("ow/baseCharacters.html.twig");
    }

    #[Route("/characters", name: "characters")]
    public function listCharacters(EntityManagerInterface $doctrine){
        $repository = $doctrine->getRepository(Characters::class);
        $characters = $repository->findAll();
        return $this->render("ow/listCharacters.html.twig",["characters"=>$characters]);
    }

    #[Route("/details/{id}", name: "showDetails")]
    public function showDetailsCharacter(EntityManagerInterface $doctrine, $id){
        $repository = $doctrine->getRepository(Characters::class);
        $character = $repository->find($id);
        return $this->render("ow/detailCharacter.html.twig", ["character"=>$character]);
    }

    #[Route("/add", name: "addCharacter")]
    public function addCharacter(EntityManagerInterface $doctrine){
        $char1 = new Characters();
        $char1->setName("Mercy");
        $char1->setRole("Support");
        $char1->setImage("https://d1u1mce87gyfbn.cloudfront.net/hero/mercy/hero-select-portrait.png");
        $char1->setFaccion("Overwatch");

        $char2 = new Characters();
        $char2->setName("Reinhardt");
        $char2->setRole("Tank");
        $char2->setImage("https://d1u1mce87gyfbn.cloudfront.net/hero/reinhardt/hero-select-portrait.png");
        $char2->setFaccion("Overwatch");

        $char3 = new Characters();
        $char3->setName("Reaper");
        $char3->setRole("DPS");
        $char3->setImage("https://d1u1mce87gyfbn.cloudfront.net/hero/reaper/hero-select-portrait.png");
        $char3->setFaccion("Talon");
       
        $char4 = new Characters();
        $char4->setName("Ashe");
        $char4->setRole("DPS");
        $char4->setImage("https://d1u1mce87gyfbn.cloudfront.net/hero/ashe/hero-select-portrait.png");
        $char4->setFaccion("Deadlock");

        $doctrine ->persist($char1);
        $doctrine ->persist($char2);
        $doctrine ->persist($char3);
        $doctrine ->persist($char4);

        $doctrine ->flush();
        return new Response ("insertados correctamente");

    }

    #[Route("/addDescription", name: "addDescription")]
    public function addDescription(EntityManagerInterface $doctrine){
        $descriptions = [
            "El traje Valkyrie de Mercy la ayuda a mantenerse cerca de sus compañeros de equipo cual ángel de la guarda, y los sana, los resucita o los fortalece con el haz que emana de su bastón caduceo.",
            "Ataviado de una armadura potenciada y equipado con su martillo, Reinhardt realiza embestidas propulsadas por el campo de batalla y defiende a su escuadrón con un enorme campo protector.",
            "Sus escopetas infernales, la fantasmal habilidad de volverse inmune al daño y el poder de desplazarse por las tinieblas hacen de Reaper uno de los seres más letales de la Tierra.",
            "Ashe dispara su rifle rápidamente desde la cadera o usa la mira para encañonar y realizar un disparo que inflige mucho daño. Puede volar a sus enemigos con dinamita y su recortada es tan potente que puede alejarla de sus enemigos. Y Ashe no está sola: puede llamar a su aliado ómnico, Bob, para que se una a la pelea cuando sea necesario."
        ];
        $repository = $doctrine->getRepository(Characters::class);
        $characters = $repository->findAll();
        foreach($characters as $index =>$character){
            $character->setDescription($descriptions[$index]);
            $doctrine->persist($character);
        }
        $doctrine->flush();
        return new Response ("actualizados correctamente");
    }

    #[Route("/newCharacter", name: "newCharacter")]
    public function addFormCharacter(EntityManagerInterface $doctrine, Request $request){
        $form = $this->createForm(CharactersFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $character = $form->getData();
            $doctrine->persist($character);
            $doctrine->flush();
            $this->addFlash("Success", "Personaje insertado correctamente");
            return $this->redirectToRoute("characters");
        }
        return $this->renderForm("ow/formCharacters.html.twig", ["formCharacter" => $form]);
    }

    #[Route("/editCharacter/{id}", name: "editCharacter")]
    public function editCharacter(EntityManagerInterface $doctrine, Request $request, $id){
        $repository = $doctrine->getRepository(Characters::class);
        $findCharacter = $repository->find($id);
        $form = $this->createForm(CharactersFormType::class, $findCharacter);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $character = $form->getData();
            $doctrine->persist($character);
            $doctrine->flush();
            $this->addFlash("Success", "Personaje editado correctamente");
            return $this->redirectToRoute("characters");
        }
        return $this->renderForm("ow/formCharacters.html.twig", ["formCharacter" => $form]);
    }
}
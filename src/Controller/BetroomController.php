<?php

namespace App\Controller;

use App\Entity\Betroom;
use App\Form\BetroomCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class BetroomController extends AbstractController
{


    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
       $this->security = $security;
       $this->em = $entityManager;
    }


    /**
     * @Route("/betroom/create", name="create_betroom")
     */
    public function create(Request $request): Response
    {
        $user = $this->security->getUser();

        $betroom = new Betroom();

        $form = $this->createForm(BetroomCreateType::class, $betroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $betroom = $form->getData();

            
            $this->em->persist($betroom);
            $this->em->flush();

            $this->addFlash('success', 'betroom created with success');

            return $this->redirectToRoute('app_hub');
        }

        return $this->render('betroom/create.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

  
}

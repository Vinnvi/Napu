<?php

namespace App\Controller;

use App\Entity\Betroom;
use App\Entity\BetroomUser;
use App\Entity\User;
use App\Form\BetroomCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class BetroomController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
       $this->em = $entityManager;
    }

    /**
     * @Route("/betroom/{id}", name="betroom.view", requirements={"id":"\d+"})
     * @param Betroom $betroom
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function view(Betroom $betroom)
    {
        $user = $this->getUser();

        if($betroom === null) {
            //TODO handle error 
        }

        return $this->render('betroom/view.html.twig', ['user' => $user, 'betroom' => $betroom]);
    }


    /**
     * @Route("/betroom/create", name="create_betroom")
     */
    public function create(Request $request): Response
    {
        $user = $this->getUser();

        $betroom = new Betroom();

        $form = $this->createForm(BetroomCreateType::class, $betroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $betroom = $form->getData();

            
            $this->em->persist($betroom);
            $this->em->flush();

            $this->addUserToBetRoom($user, $betroom, 0);

            $this->addFlash('success', 'betroom created with success');

            return $this->redirectToRoute('app_hub');
        }

        return $this->render('betroom/create.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @param User $user
     * @param Betroom $betroom
     * @param integer $status
     */
    public function addUserToBetRoom(User $user, Betroom $betroom, $status = null)
    {
        $betroomUser = new BetroomUser();
        
        $betroomUser->setUser($user);
        $betroomUser->setBetroom($betroom);
        if($status !== null) {
            $betroomUser->setStatus($status);
        } 
        
        $this->em->persist($betroomUser);
        $this->em->flush();

        return;
    }



  
}

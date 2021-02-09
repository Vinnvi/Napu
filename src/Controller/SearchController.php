<?php

declare(strict_types=1);

namespace App\Controller;

use Elastica\Util;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


final class SearchController extends AbstractController
{

    /**
     * @Route("/search",  name="search")
     */
    public function search(Request $request, SessionInterface $session, TransformedFinder $usersFinder): Response
    {
        $q = (string) $request->query->get('q', '');
        $results = !empty($q) ? $usersFinder->findHybrid(Util::escapeTerm($q)) : [];
        $session->set('q', $q);


        return $this->render('search.html.twig', compact('results', 'q'));
    }
}
<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Elasticsearch\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class AutoCompleteController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/ac", name="auto_complete")
     */
    public function index(Client $client, Request $request)
    {
        $query = $request->query->get('q');
        $usersFinal = [];
        if($query !== null)
        {
            $users = $this->viewTest($client, $request);
            $users = json_decode($users->getContent(), true);
            foreach($users['users'] as $user)
            {
                $myuser = $this->userRepository->findOneByUsername($user['value']);
                if($myuser !== null) {
                    array_push($usersFinal, $myuser);
                }
            }
        }
        return $this->render('test.html.twig', [
            'controller_name' => 'AutoCompleteController',
            'users' => $usersFinal,
        ]);
    }




    /**
     * @Route("/ac/search", name="auto_complete_search", methods="GET")
     */
    public function viewTest(Client $client, Request $request)
    {
        $user = $this->getUser();
        $indexDefinition = ['index' => 'users'];
        $query = $request->query->get('q');

        $result = $client->search(
            array_merge(
                $indexDefinition,
                ['body' => [
                    'query' => [
                        'match' => [
                            'username' => [
                                'query' => $query,
                                "operator" => "and",
                                "fuzziness" => 2,
                                "analyzer" => "standard"
                            ]
                        ],
                    ],
                    'size' => 3
                ]]
            ));

        $data = array_map(function ($item) {
            return ['value' => $item['_source']['username']];
        }, $result['hits']['hits']);

        return $this->json([
            'users' => $data
        ]);
    }

    /**
     * fill database with teams 
     */
    public function fill_teams()
    {
        $response = $this->client->request('GET', 'https://api.football-data.org/v2/competitions/FL1/matches?matchday=20', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => '145e132ff7154506ac62251d4b9c16bf'
            ],
        ]);

        dump($response->getStatusCode());
        dump($response->toArray());

        return;
    }
}
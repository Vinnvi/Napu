<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use App\Entity\Team;
use App\Entity\League;
use App\Entity\MatchDay;
use App\Entity\Matcha;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class SportFixtures extends Fixture
{

    private $client;

    private $em;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }


    public function load(ObjectManager $manager)
    {
        $this->fillSports($manager);
        $this->fillLeagues($manager);
        $this->fillFootballTeams($manager);
    }


    public function fillLeagues(ObjectManager $manager,$sport = null)
    {
        //tempo : just league 1 for moment
        $sport = $this->em->getRepository(Sport::class)->findOneByName('Soccer');

        if($sport === null)
        {
            return;
        }

        $league = new League();
        $league->setName('Ligue 1');
        $league->setShortName('FL1');

        $manager->persist($league);
        $manager->flush();

        //$this->fillCurrentSeason($manager, $league, $sport);
    }

    public function fillCurrentSeason(ObjectManager $manager, Season $season, Sport $sport)
    {
        $matchdayCount = 1;

        $response = $this->client->request('GET', 'https://api.football-data.org/v2/competitions/FL1/matches?matchday='.$matchday, [
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => '145e132ff7154506ac62251d4b9c16bf'
            ],
        ]);

        while($response === 200)
        {
            $matchday = new MatchDay();
            $matchDay->setNumber($matchDayCount);
            $matchDay->setSeason($season);

            $manager->persist($matchDay);

            $matches = $response->toArray()['matches'];
            foreach($matches as $match)
            {
                $matcha = new Matcha();
                $matcha->setMatchDay($matchDay);
                $matcha->setSport($sport);
                $matcha->setStatus($match['status']);

                $matchaInfos = Array();

                $manager->persist($matcha);
            }
            $matchday += 1;

            $response = $this->client->request('GET', 'https://api.football-data.org/v2/competitions/FL1/matches?matchday='.$matchday, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Auth-Token' => '145e132ff7154506ac62251d4b9c16bf'
                ],
            ]);
        }

        $manager->flush();
    }

    public function fillFootballTeams(ObjectManager $manager)
    {  
        //get football sport entity
        $sport = $this->em->getRepository(Sport::class)->findOneByName('Soccer');

        if($sport === null)
        {
            return;
        }

        $response = $this->client->request('GET', 'https://api.football-data.org/v2/competitions/FL1/teams', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => '145e132ff7154506ac62251d4b9c16bf'
            ],
        ]);

        if($response->getStatusCode() !== 200)
        {
            return;
        }

        $teams = $response->toArray()['teams'];
        foreach($teams as $team)
        {
            $newteam = new Team();
            $newteam->setName($team['shortName']);
            $newteam->setFullName($team['name']);
            $newteam->setSport($sport);

            $manager->persist($newteam);
        }

        $manager->flush();
    }
    

    public function fillSports(ObjectManager $manager)
    {
        $response = $this->client->request(
            'GET',
            'https://www.thesportsdb.com/api/v1/json/1/all_sports.php'
        );
               

        if($response->getStatusCode() !== 200)
        {
            return;
        }

        $content = $response->toArray();

        if(array_key_exists('sports',$content) === false)
        {
            return;
        }

        foreach($content['sports'] as $element)
        {
            $sport = new Sport();
            $sport->setName($element['strSport']);
            $manager->persist($sport);
        }

        $manager->flush();
    }
}

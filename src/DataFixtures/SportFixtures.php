<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SportFixtures extends Fixture
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public function load(ObjectManager $manager)
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

<?php

namespace App\Command;

use App\Repository\UserRepository;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class ElasticsearchCreateIndexCommand extends Command
{
    protected static $defaultName = 'app:elasticsearch:create-index';

    protected $client;

    protected $userRepository;

    /**
     * @var array
     */
    protected $indexDefinition;

    protected function configure()
    {
        $this
            ->setDescription('Build new index from scratch and populate')
        ;
    }

    /**
     * FeedProductsCommand constructor.
     * @param Client $client
     */
    public function __construct(Client $client, UserRepository $userRepository)
    {  
        $this->client = $client;
        parent::__construct(null);
        $this->userRepository = $userRepository;
        $this->indexDefinition = ['index' => 'users'];

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('CREATING INDEX....');
        $index = $this->createIndex();
        $io->note('CREATION DONE');
        $io->note('FEEDING INDEX....');
        $this->feed_index($index);

        return Command::SUCCESS;
    }

    private function feed_index($index)
    {
        $users = $this->userRepository->findAll();
        foreach($users as $user)
        {
            $doc = array_merge(
                $this->indexDefinition,
                [
                    'id' => $user->getId(),
                    'body' => [
                        'username' => $user->getUsername(),
                        'firstName' => $user->getFirstName(),
                        'lastName' => $user->getLastName(),
                    ]
                ]
            );
            $this->client->index($doc);
        }
    }

    private function createIndex()
    {
        if($this->client->indices()->exists($this->indexDefinition)) {
            $this->client->indices()->delete($this->indexDefinition);
        }
        $index = $this->client->indices()->create(
            array_merge($this->indexDefinition,[
                'index' => 'users',
                'body' => [
                    'settings' => [
                        'number_of_shards' => 1,
                        'number_of_replicas' => 0,
                        "analysis" => [
                            "analyzer" => [
                                "autocomplete" => [
                                    "tokenizer" => "autocomplete",
                                    "filter" => ["lowercase"]
                                ]
                            ],
                            "tokenizer" => [
                                "autocomplete" => [
                                    "type" => "edge_ngram",
                                    "min_gram" => 2,
                                    "max_gram" => 20,
                                    "token_chars" => [
                                        "letter",
                                        "digit"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "mappings" => [
                        "properties" => [
                            "username" => [
                                "type" => "text",
                                "analyzer" => "autocomplete",
                                "search_analyzer" => "standard"
                            ]
                        ]
                    ]
                ]
            ])
        );
        return $index;
    }
}

<?php

/*
 * GetFruity (This file is Command and responsible for getting data from fruity api and store into DB and send mail to admin functionality)
 * Author : Amar Shah
 * Company : QuanticEdge Software Solutions
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use App\Scraper\Contracts\SourceInterface;
use App\Entity\Fruity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetFruity extends Command
{
    protected $source;
    protected $doctrine;
    protected $logger;
    protected $client;
    protected $params;

    protected static $defaultName = "app:get:fruity";

    public function __construct(
        SourceInterface $source,
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        HttpClientInterface $client
    ) {
        $this->source = $source;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->client = $client;

        parent::__construct();
    }
    protected function configure()
    {
        $this->setDescription('Get Fruity')->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->doctrine->getConnection()->beginTransaction(); // suspend auto-commit

        try {
            $this->logger->info('Get Fruits Start....');
            dump('Get Fruits Start....');
            dump('Please wait....');
            $response = $this->client->request(
                'GET',
                'https://fruityvice.com/api/fruit/all'
            );

            $statusCode = $response->getStatusCode();
            $content = $response->getContent();
            $content = $response->toArray();

            if ($statusCode == 200) {
                foreach ($content as $key => $value) {
                    $genus = $value["genus"];
                    $name = $value["name"];
                    $family = $value["family"];
                    $id = $value["id"];
                    $order = $value["order"];
                    $nutritions = $value["nutritions"];
                    $query = $this->doctrine->getManager()->createQuery("SELECT f.name FROM App\Entity\Fruity as f where f.name='" . trim($name) . "'");
                    $data = $query->getOneOrNullResult();
                    // added to DB only if dose not exists 
                    if (is_null($data)) {
                        try {
                            $fruity = new Fruity();
                            $this->logger->info("name :" . $name);
                            $fruity->setName($name);

                            $this->logger->info("fruity id :" . $id);
                            $fruity->setFruitId($id);

                            $this->logger->info("genus :" . $genus);
                            $fruity->setGenus($genus);

                            $this->logger->info("family :" . $family);
                            $fruity->setFamily($family);

                            $this->logger->info("order :" . $order);
                            $fruity->setFOrder($order);

                            $fruity->setIsFav(0);

                            $fruity->setNutritions($nutritions);

                            $this->doctrine->getManager()->persist($fruity);

                            $to = 'popab23286@necktai.com'; // set temp mail for testing (use mailtrap.io for testing)
                            $subject = 'New Fruit Added';
                            $message = 'Hello, New fruit is Added : ' . $name;
                            $smtp_host = $_ENV['SMTP_HOST'];
                            $smtp_port = $_ENV['SMTP_PORT'];
                            $smtp_username = $_ENV['SMTP_USERNAME'];
                            $smtp_password = $_ENV['SMTP_PASSWORD'];

                            // Create the Transport
                            $transport = (new \Swift_SmtpTransport($smtp_host, $smtp_port))
                                ->setUsername($smtp_username)
                                ->setPassword($smtp_password);
                            // Create the Mailer using your created Transport
                            $mailer = new \Swift_Mailer($transport);
                            // Create a message
                            $message = (new \Swift_Message($subject))
                                ->setFrom(['sender@example.com' => 'Sender'])
                                ->setTo([$to])
                                ->setBody($message);
                            // Send the message
                            $mailer->send($message);
                        } catch (\Exception $e) {
                            dump($e->getMessage());
                        }
                    }
                }
            } else {
                dump("Somethings Went Wrong!");
                $this->logger->error('Somethings Went Wrong!');
            }
            $this->doctrine->getManager()->flush();
            $this->doctrine->getConnection()->commit();
            $this->logger->info('Get Fruits End....');
            dump('Get Fruits End....');
            return true;
        } catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            dump($e->getMessage());
            $this->logger->error('An error occurred' . $e->getMessage());
        }
    }
}

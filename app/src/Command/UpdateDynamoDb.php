<?php
namespace App\Command;

use App\DynamoDb\Model;
use App\DynamoDb\User;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDynamoDb extends Command
{
    /**
     * @var string
     */
    private $appStage;

    /**
     * @var Model
     */
    private $dynamoDbModel;

    /**
     * @var DynamoDbClient
     */
    private $dynamoDbClient;

    public function __construct(string $appStage, Model $model, DynamoDbClient $dynamoDbClient)
    {
        $this->appStage = $appStage;
        $this->dynamoDbModel = $model;
        $this->dynamoDbClient = $dynamoDbClient;
        parent::__construct('app:update-dynamo-db');
    }

    protected function configure()
    {
        $this
            ->setDescription('Update the DynamoDb tables')
            ->setHelp('Help to keep the schema consistent with the code')
            ->addOption(
                'delete',
                'd',
                InputOption::VALUE_NONE,
                'When this param is used the database get destroyed and recreated (disabled in preprod and production stages)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $deleteMode = false;
        if(!in_array($this->appStage, ['preprod', 'production'])){
            $deleteMode = $input->getOption('delete');
        }
        $schemas = [
            User::schema(),
        ];
        if(true === $deleteMode){
            $output->writeln('Resetting database!');
        } else {
            $output->writeln('Updating database!');
        }
        $deletedTableCount = 0;
        $createdTableCount = 0;
        if (true === $deleteMode) {
            foreach ($schemas as $schema) {
                try {
                    Model::$client->deleteTable([
                        "TableName" => $schema['TableName']
                    ]);
                    $deletedTableCount++;
                } catch (DynamoDbException $e) {
                    $createdTableCount++;
                }
                Model::$client->createTable($schema);
            }
            $output->writeln($deletedTableCount.' table(s) reinitialized');
        } else {
            foreach ($schemas as $schema) {
                try {
                    Model::$client->createTable($schema);
                    $createdTableCount++;
                } catch (DynamoDbException $e) {}
            }
        }
        $output->writeln($createdTableCount.' table(s) created');
        $output->writeln('Done!');
        return 0;
    }
}

<?php
namespace App\Command;

use App\Entity\Article;
use App\Entity\Rikudou;
use App\Entity\User;
use Aws\DynamoDb\Exception\DynamoDbException;
use JLucki\ODM\Spark\Exception\TableAlreadyExistsException;
use JLucki\ODM\Spark\Exception\TableDoesNotExistException;
use JLucki\ODM\Spark\Spark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDynamoDb extends Command
{

    public function __construct(
        private string $appStage,
        private Spark $spark,
    ) {
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
            Article::class,
            Rikudou::class,
            User::class
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
                    $this->spark->getTable($schema);
                    $this->spark->deleteTable($schema);
                    $deletedTableCount++;
                    $this->spark->createTable($schema);
                } catch (TableDoesNotExistException $e) {
                    $this->spark->createTable($schema);
                    $createdTableCount++;
                }
            }
            $output->writeln($deletedTableCount.' table(s) reinitialized');
        } else {
            foreach ($schemas as $schema) {
                try {
                    $this->spark->createTable($schema);
                    $createdTableCount++;
                } catch (TableAlreadyExistsException $e) {}
            }
        }
        $output->writeln($createdTableCount.' table(s) created');
        $output->writeln('Done!');
        return 0;
    }
}

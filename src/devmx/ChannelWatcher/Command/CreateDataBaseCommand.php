<?php
namespace devmx\ChannelWatcher\Command;
use devmx\ChannelWatcher\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use devmx\ChannelWatcher\Storage\DbalStorage\DataBaseManager;
use Doctrine\DBAL\Connection;

/**
 *
 * @author drak3
 */
class CreateDataBaseCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var DataBaseManager
     */
    protected $manager;
    
    /**
     * @var Connection
     */
    protected $connection;
    protected $tableName;
    
    public function __construct(Connection $con, DataBaseManager $manager, $tableName) {
        $this->manager = $manager;
        $this->tableName = $tableName;
        $this->connection = $con;
    }
    
    protected function configure() {
        $this->setName('database:create_table');
    }
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $this->manager->createTable($this->connection, $this->tableName);
    }
}

?>

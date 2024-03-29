<?php
declare(strict_types=1);

namespace App\Command;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateEventStreamCommand
 * @package App\Command
 */
final class CreateEventStreamCommand extends Command
{
	/**
	 * @var EventStore
	 */
    private $eventStore;

	/**
	 * CreateEventStreamCommand constructor.
	 * @param EventStore $eventStore
	 */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('event-store:event-stream:create')
            ->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eventStore->create(new Stream(new StreamName('event_stream'), new \ArrayIterator([])));

        $output->writeln('<info>Event stream was created successfully.</info>');
    }
}

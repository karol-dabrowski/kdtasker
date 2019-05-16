<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Class RunAllProjectionsCommand
 * @package App\Command
 */
final class RunAllProjectionsCommand extends Command
{

	protected function configure()
	{
		$this->setName('event-store:projections:run-all')
		     ->setDescription('Run all projections');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|void|null
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$migrations = ['task_projection'];

		foreach ($migrations as $migration) {
			$process = new Process(
				'php bin/console event-store:projection:run ' . $migration
			);
			$process->start();
			$process->waitUntil(function ($type, $outputMessage) use ($migration) {;
				return strpos($outputMessage, 'Starting projection ' . $migration);
			});
			$process->stop();
		}

	}
}

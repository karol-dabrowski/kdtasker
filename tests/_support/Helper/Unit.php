<?php
namespace App\Tests\Helper;

use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;

class Unit extends \Codeception\Module
{
	/**
	 * @var AggregateTranslator
	 */
	private $aggregateTranslator;

	/**
	 * @param AggregateRoot $aggregateRoot
	 * @return array
	 */
	public function popRecordedEvents(AggregateRoot $aggregateRoot): array
	{
		return $this->getAggregateTranslator()->extractPendingStreamEvents($aggregateRoot);
	}

	/**
	 * @param string $aggregateRootClass
	 * @param array $events
	 * @return mixed
	 */
	public function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events)
	{
		return $this->getAggregateTranslator()->reconstituteAggregateFromHistory(
			AggregateType::fromAggregateRootClass($aggregateRootClass),
			new \ArrayIterator($events)
		);
	}

	/**
	 * @return AggregateTranslator
	 */
	private function getAggregateTranslator(): AggregateTranslator
	{
		if (null === $this->aggregateTranslator) {
			$this->aggregateTranslator = new AggregateTranslator();
		}

		return $this->aggregateTranslator;
	}
}

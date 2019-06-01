<?php
declare( strict_types=1 );

namespace Tasker\Infrastructure\Projection\Task;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\ReadModelProjector;

/**
 * Class UserTasksProjection
 * @package Tasker\Infrastructure\Projection\Task
 */
class UserTasksProjection implements ReadModelProjection
{
	/**
	 * @param ReadModelProjector $projector
	 * @return ReadModelProjector
	 */
	public function project(ReadModelProjector $projector): ReadModelProjector
	{
		$projector->fromStream('event_stream')->whenAny(
			function ($state, AggregateChanged $event) {
				$readModel = $this->readModel();
				$readModel($event);
			}
		);

		return $projector;
	}
}
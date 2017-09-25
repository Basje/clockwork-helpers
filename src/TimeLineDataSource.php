<?php namespace InThere\ClockworkHelpers;


use Clockwork\Request\Request;
use Clockwork\Request\Timeline;
use InThere\ClockworkHelpers\Contracts\TimeLineDataSourceInterface;

class TimeLineDataSource implements TimeLineDataSourceInterface
{

    /**
     * @var Timeline
     */
    private $timeLine;

    /**
     * Constructor.
     *
     * @param Timeline $timeLine
     */
    public function __construct(Timeline $timeLine)
    {
        $this->timeLine = $timeLine;
    }

    /**
     * @param Request $request
     */
    public function resolve(Request $request)
    {
        $this->timeLine->finalize();
        $request->timelineData = array_merge($request->timelineData, $this->timeLine->toArray());
    }

    /**
     * @param string $name
     * @param string $description
     * @param integer $time
     * @param array $data
     */
    public function startEvent($name, $description, $time = null, array $data = [])
    {
        $this->timeLine->startEvent($name, $description, $time, $data);
    }

    /**
     * @param string $name
     */
    public function endEvent($name)
    {
        $this->timeLine->endEvent($name);
    }

    public function addEvent($name, $description, $startTime, $endTime, array $data = [])
    {
        $this->timeLine->addEvent($name, $description, $startTime, $endTime, $data);
    }
}
<?php

namespace imsuperlative\scheduleWorker;

use Illuminate\Support\Str;

class ScheduleQueue
{
    protected const
        COMMAND = 'queue:work',
        ARGUMENT_PREFIX = '--';

    /**
     * @var string $connection
     */
    private $connection;

    /**
     * @var array $settings
     */
    private $settings;

    /**
     * @var int $numproc
     */
    private $numproc;

    public function __construct(array $config = [])
    {
        $this->settings = array_merge(config('scheduleWorker.default'), $config);
        $this->setConnection();
        $this->setNumproc();

        if ($this->connection === 'sqs-fifo') {
            $this->settings['queue'] = $this->parseQueue();
        }
    }

    public function makeCommand(): string
    {
        $command = [static::COMMAND, $this->connection];

        foreach ($this->settings as $key => $value) {
            $command[] = static::parseOption($key, $value);
        }

        return $this->make(array_filter($command));
    }

    public function parseQueue(): string
    {
        $queues = [];
        foreach (explode(',', $this->settings['queue']) as $queue) {
            $queues[] = Str::endsWith($queue, '.fifo') ?: $queue . '.fifo';
        }

        return implode(',', $queues);
    }

    public static function parseOption(string $key, $value): string
    {
        $command = static::ARGUMENT_PREFIX . $key;

        if (is_bool($value)) {
            return $value === false ? '' : $command;
        }

        return "$command=$value";
    }

    public function getNumproc(): int
    {
        return $this->numproc;
    }

    public function isMultiple(): bool
    {
        return $this->numproc > 1;
    }

    private function setConnection()
    {
        $this->connection = $this->settings['connection'] ?: 'sync';
        unset($this->settings['connection']);
    }

    private function setNumproc()
    {
        $this->numproc = $this->settings['numproc'];
        unset($this->settings['numproc']);
    }

    private function make(array $config): string
    {
        return implode(' ', $config);
    }
}

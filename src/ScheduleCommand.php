<?php

namespace imsuperlative\scheduleWorker;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

class ScheduleCommand
{
    public static function make(bool $unique = false): array
    {
        $commands = [];

        foreach (config('scheduleWorker.queues') as $name => $config) {
            $command = new ScheduleQueue($config);

            // todo clean up
            if ($command->isMultiple()) {
                for ($i = 0; $i < $command->getNumproc(); $i++) {
                    $hash = $unique ? $i . '_' . gethostname() : (string) $i;
                    $commands[] = $command->makeCommand() . static::mutexNameHash($hash);
                }
            } else {
                $commands[] = $command->makeCommand();
            }
        }

        return $commands;
    }

    public static function dispatch(Schedule $schedule, string $command): Event
    {
        $event = $schedule
            ->command($command)
            ->runInBackground()
            ->withoutOverlapping()
            ->everyMinute()
            ->when(config('scheduleWorker.enabled'));

        if (config('scheduleWorker.log.enabled')) {
            static::log($event);
        }

        return $event;
    }

    protected static function mutexNameHash(string $hash): string
    {
        return '; echo ' . $hash;
    }

    protected static function log(Event $event)
    {
        $event
            ->onSuccess(static function () use ($event) {
                Log::channel(static::getLogChannel())->info($event->command);
            })
            ->onFailure(static function () use ($event) {
                Log::channel(static::getLogChannel())->error($event->command);
            });
    }

    protected static function getLogChannel(): string
    {
        return config('scheduleWorker.log.channel');
    }
}

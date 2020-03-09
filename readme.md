    use imsuperlative\scheduleWorker\ScheduleCommand;
    ...
    
    protected function schedule(Schedule $schedule)
    {
        foreach (ScheduleCommand::make() as $command) {
            ScheduleCommand::dispatch($schedule, $command);
        }
    }

this is proof of concept and should not be run in production environment.

todo  
echo might destroy logging output of schedule

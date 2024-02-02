<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Str;
class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees that event is near';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $event = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time',[now(), now()->addDay()])
            ->get();
        $eventCount = $event->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}.");

        $event->each(
            fn ($event)=> $event->attendees->each(
                fn($attendee) => $attendee->user->notify(
                    new EventReminderNotification(
                        $event
                    )
                )
            )
        );
        $this->info('Reminder notifications sens successfully!!');
    }
}

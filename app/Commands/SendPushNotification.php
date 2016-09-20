<?php
namespace App\Commands;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\SerializesModels;
use Kurio\Common\Models\PushNotification;

class SendPushNotification extends Command implements SelfHandling
{
    use SerializesModels;

    const QUEUE_NAME = 'push-notification';

    /**
     * @var PushNotification
     */
    protected $notification;

    /**
     * @var \DateTime
     */
    protected $time_to_send = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PushNotification $notification, $time_to_send = null)
    {
        $this->notification = $notification;
        if (!empty($time_to_send)) {
            if (is_int($time_to_send)) {
                $this->time_to_send = Carbon::createFromTimestampUTC($time_to_send);

            } elseif ($time_to_send instanceof \DateTime) {
                $this->time_to_send = Carbon::instance($time_to_send);
            }
        }
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(Queue $queue)
    {
        $job = '\Kurio\Console\Job  s\PushNotification\ProcessPushRequest';
        $data = ['notification' => $this->notification->id];

        if (empty($this->time_to_send)) {
            $queue->pushOn(static::QUEUE_NAME, $job, $data);

        } else {
            $queue->laterOn(static::QUEUE_NAME, $this->time_to_send, $job, $data);
        }
    }
}
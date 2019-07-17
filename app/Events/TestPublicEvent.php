<?php

namespace App\Events;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestPublicEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var Generator
     */
    private $faker;
    /**
     * @var array
     */
    private $data = [];

    /**
     * Create a new event instance.
     *
     * @param string $subject
     */
    public function __construct (string $subject)
    {
        $this->subject = $subject;
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn ()
    {
        return new Channel('test');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith ()
    {
        return [
            'subject' => $this->subject,
            'content' => $this->faker->sentence()
        ];
    }
}

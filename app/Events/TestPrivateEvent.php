<?php

namespace App\Events;

use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TestPrivateEvent implements ShouldBroadcastNow
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
     * @var User
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @param string $subject
     */
    public function __construct (string $subject)
    {
        $this->subject = $subject;
        $this->faker = Factory::create('fr_FR');
        $this->user = Auth::user();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn ()
    {
        return new PrivateChannel('test.' . $this->user->getAttribute('id'));
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith ()
    {
        return [
            'subject' => $this->subject . ' (' . $this->user->getAttribute('name') . ')',
            'content' => $this->faker->sentence()
        ];
    }
}

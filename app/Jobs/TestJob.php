<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    private $users;

    /**
     * Create a new job instance.
     *
     * @param Collection $users
     */
    public function __construct (Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        $first = microtime(true);
        foreach ($this->users as $user) {
            dump('Handling stuff for << ' . $user->name . ' >>');
            usleep(100 * 1000); //100 ms
        }
        $last = microtime(true);
        dump('Finished in ' . ($last - $first) . ' seconds!');
    }
}

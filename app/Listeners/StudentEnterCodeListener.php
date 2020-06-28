<?php

namespace App\Listeners;

use App\Events\StudentEnterCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StudentEnterCodeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StudentEnterCode  $event
     * @return void
     */
    public function handle(StudentEnterCode $event)
    {
        //
    }
}

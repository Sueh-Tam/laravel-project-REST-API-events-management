<?php

namespace Tests;

use App;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        //$this->assertFalse(false, 'This test fails intentionally.');
        $this->assertTrue(false, "deu bom, mas que bom");
        //$this->assertFalse(false,"deu ruim, mas que bom");
        return $app;
    }
}

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
        //return $this->assertFalse(true, 'This test fails intentionally.');
        $this->asserTrue(false, 'FALHOU COM SUCESSO');
        
        return $app;
    }
}

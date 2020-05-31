<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PhpUnitTest extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'php:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para rodar os testes com Php Unit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        exec("./vendor/phpunit/phpunit/phpunit", $output);

        $this->info(implode(PHP_EOL, $output));
    }
}

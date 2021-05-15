<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {classRepositoryName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Repository';

    private $classRepositoryName;


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
        $this->generateNameRepositoryClass($this->argument('classRepositoryName'));

        $pathRepositories = './app/Repositories';

        $commandMakeDirectory = "mkdir {$pathRepositories}\n";
        $commandTouchRepositoryFile = "touch {$pathRepositories}/{$this->classRepositoryName}.php\n";

        $templateRepositoryClassFile = $this->generateClassContent();

        $commandFillRepositoryClassFile = "echo $templateRepositoryClassFile > {$pathRepositories}/{$this->classRepositoryName}.php";

        $commandCreateRepository =   $commandMakeDirectory .
            $commandTouchRepositoryFile . $commandFillRepositoryClassFile;

        exec($commandCreateRepository, $output);

        $this->info(implode(PHP_EOL, $output));
        //
    }


    private function generateClassContent()
    {

        $classContent = "\"<?php\nnamespace App\Repositories;\nuse App\Repositories\Interfaces\AcquisitionRepositoryInterface;\nuse App\Repositories\ModelRepository;\nclass {$this->classRepositoryName} extends ModelRepository implements AcquisitionRepositoryInterface\n{\n}\"";

        return $classContent;
    }


    private function generateNameRepositoryClass($nameClass)
    {
        $nameRepositoryClass =  "{$nameClass}Repository";
        $this->classRepositoryName = $nameRepositoryClass;
    }
}

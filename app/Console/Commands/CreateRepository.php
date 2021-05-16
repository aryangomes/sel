<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {classRepositoryName} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Repository';

    /** @var String $classRepositoryName classRepositoryName */
    private $classRepositoryName;

    /** @var String $classModelName classModelName */
    private $classModelName;

    /** @var Filesystem $filesystem filesystem */
    private $filesystem;

    /** @var String $classRepositoryName classRepositoryName */
    private static $pathRepositories = './app/Repositories';

    private static $pathModels = './app/Models';

    /** @var Boolean $createRepositoryWasSuccessfully createRepositoryWasSuccessfully */
    private  $createRepositoryWasSuccessfully;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {

        $this->createRepositoryWasSuccessfully = false;
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->classModelName = $this->option('model');

        $this->generateNameRepositoryClass($this->argument('classRepositoryName'));


        $this->makeDirectoryRepositories();

        $this->makeRepositoryClass();

        $this->resultOfCommand();




        /*   $commandTouchRepositoryFile = "touch {$this::$pathRepositories}/{$this->classRepositoryName}.php\n";

        $templateRepositoryClassFile = $this->generateClassContent();

        $commandFillRepositoryClassFile = "echo $templateRepositoryClassFile > {$this::$pathRepositories}/{$this->classRepositoryName}.php";

        $commandCreateRepository =   $commandMakeDirectory .
            $commandTouchRepositoryFile . $commandFillRepositoryClassFile;

        exec($commandCreateRepository, $output); 

        $this->info(implode(PHP_EOL, $output));*/
        //
    }


    private function generateClassContent()
    {

        $classContent = "<?php\nnamespace App\Repositories;\nuse App\Repositories\Interfaces\AcquisitionRepositoryInterface;\nuse App\Repositories\ModelRepository;\nclass {$this->classRepositoryName} extends ModelRepository implements AcquisitionRepositoryInterface\n{\n}";

        $linesHeaderContentClass = [
            "<?php\n",
            "namespace App\Repositories;\n",
            "use App\Repositories\Interfaces\AcquisitionRepositoryInterface;\n",
            "use App\Repositories\ModelRepository;\n",
        ];

        if (isset($this->classModelName)) {
            array_push($linesHeaderContentClass, "use App\Models\\{$this->classModelName};\n");
        }


        $linesBodyContentClass = [

            "class {$this->classRepositoryName} extends ModelRepository implements AcquisitionRepositoryInterface",
            "{",
            "/**",
            "*",
            "*",
            "* @param {$this->argument('classRepositoryName')} {$this->generateNameRepositoryClassModel($this->argument('classRepositoryName'))}\n",
            "*/",
            "public function __construct({$this->argument('classRepositoryName')} \${$this->generateNameRepositoryClassModel($this->argument('classRepositoryName'))})\n",
            "{",
            "parent::__construct(\${$this->generateNameRepositoryClassModel($this->argument('classRepositoryName'))});\n",
            "}",

            "}\n",
        ];
        $linesContentClass = array_merge($linesHeaderContentClass, $linesBodyContentClass);
        $classContent = "";
        foreach ($linesContentClass as  $line) {
            $classContent .= "{$line}\n";
        }

        return $classContent;
    }


    private function generateNameRepositoryClass($nameClass)
    {
        $nameRepositoryClass =  "{$nameClass}Repository";
        $this->classRepositoryName = $nameRepositoryClass;
    }

    private function generateNameRepositoryClassModel($nameClass)
    {
        $nameRepositoryClassModel =  lcfirst("{$nameClass}Model");
        return  $nameRepositoryClassModel;
    }

    private function makeDirectoryRepositories()
    {
        $directoryRepositoriesExists = $this->filesystem->exists($this::$pathRepositories);
        if (!$directoryRepositoriesExists) {
            try {
                $this->filesystem->makeDirectory($this::$pathRepositories);
            } catch (\Exception $exception) {
                $this->logErrorFromException($exception);
            }
        }
    }

    private function makeRepositoryClass()
    {
        $fullPathOfFileRepositoryClass = "{$this::$pathRepositories}/{$this->classRepositoryName}.php";

        $repositoryClassExists = $this->filesystem->exists($fullPathOfFileRepositoryClass);
        if (!$repositoryClassExists) {
            try {

                $this->filesystem->put($fullPathOfFileRepositoryClass, $this->generateClassContent());

                $this->createRepositoryWasSuccessfully = true;
            } catch (\Exception $exception) {

                $this->logErrorFromException($exception);
            }
        } else {
            $this->warn("Class already exists!");
        }
    }

    private function logErrorFromException(\Exception $exception)
    {
        $this->warn($exception);
        logger(
            get_class($this),
            [
                'exception' => $exception
            ]
        );
    }


    private function resultOfCommand()
    {
        $resultOfCommand = "Not was possible create the Class Repository";

        if ($this->createRepositoryWasSuccessfully) {

            $resultOfCommand = "Class Repository was created successfully!";
            $this->info($resultOfCommand);
        } else {
            $this->error($resultOfCommand);
        }
    }
}

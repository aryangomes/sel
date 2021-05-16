<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateModelRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:modelRepository {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var String $classRepositoryName classRepositoryName */
    private $classRepositoryName;

    /** @var String $classModelName classModelName */
    private $classModelName;

    /** @var Filesystem $filesystem filesystem */
    private $filesystem;

    /** @var String $classRepositoryName classRepositoryName */
    private static $pathRepositories = './app/Repositories';

    /** @var Boolean $createModelRepositoryWasSuccessfully createModelRepositoryWasSuccessfully */
    private  $createModelRepositoryWasSuccessfully;



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->createModelRepositoryWasSuccessfully = false;

        $this->filesystem = $filesystem;

        $this->classRepositoryName = 'ModelRepositoryTeste';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {



        $this->makeDirectoryRepositories();

        $this->makeRepositoryClass();

        $this->resultOfCommand();
    }



    private function generateClassContent()
    {

        $linesHeaderContentClass = [
            "<?php\n",
            "namespace App\Repositories;\n",
            "use Illuminate\Database\Eloquent\Model;\n",
            "use App\Repositories\ModelRepository;\n",
        ];

        if (isset($this->classModelName)) {
            array_push($linesHeaderContentClass, "use App\Models\\{$this->classModelName};\n");
        }


        $linesBodyContentClass = [
            "class {$this->classRepositoryName} extends InterfacesRepositoryEloquentInterface",
            "{",
            "\t/**",
            "\t* @var Model \$model Base Model of Repository",
            "\t*/",
            "\tprotected \$model;",
            "\n",
            "\tpublic function __construct(Model \$model)",
            "\t{",
            "\t\t\$this->model = \$model;",
            "\t}",

            "}\n",
        ];


        $linesContentClass = array_merge($linesHeaderContentClass, $linesBodyContentClass);

        $classContent = "";
        foreach ($linesContentClass as  $line) {
            $classContent .= "{$line}\n";
        }

        return $classContent;
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

                $this->createModelRepositoryWasSuccessfully = true;
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
        $resultOfCommand = "Not was possible create the Class Model Repository";

        if ($this->createModelRepositoryWasSuccessfully) {

            $resultOfCommand = "Class Model Repository was created successfully!";
            $this->info($resultOfCommand);
        } else {
            $this->error($resultOfCommand);
        }
    }
}

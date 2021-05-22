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
    protected $signature = 'make:repository {classRepositoryName} {--resource} {--model=} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Repository';

    /** @var String $classRepositoryName classRepositoryName */
    private $classRepositoryName;

    /** @var String $argumentRepositoryName argumentRepositoryName */
    private $argumentRepositoryName;

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
        $this->argumentRepositoryName = $this->argument('classRepositoryName');
        $this->generateNameRepositoryClass($this->argumentRepositoryName);

        if ($this->repositoryFileExists()) {
            $this->warn(__('files.alreadyExists', [
                'file' => $this->classRepositoryName
            ]));
        }

        $this->generateRepositoryFile();
    }

    private function generateRepositoryFile()
    {

        $this->classModelName = $this->option('model');

        $this->createResourceMethods = ($this->option('resource'));


        if (!$this->repositoryModelFileExists()) {
            $createRepositoryModelCommand = "make:repositoryModel";
            if ($this->createResourceMethods) {
                $this->callSilent($createRepositoryModelCommand, [
                    '--resource' => true
                ]);
            } else {

                $this->callSilent($createRepositoryModelCommand);
            }
        }


        if (!$this->repositoryModelInterfaceExists()) {

            $createRepositoryModelInterfaceCommand = "make:modelNameRepositoryInterface";

            $this->callSilent($createRepositoryModelInterfaceCommand, [
                'modelNameRepositoryInterface' => $this->argumentRepositoryName
            ]);
        }


        $this->makeDirectoryRepositories();

        $this->makeRepositoryClass();

        $this->commandResult();
    }

    private function generateClassContent()
    {

        $repositoryNameInterface = "{$this->argumentRepositoryName}RepositoryInterface";

        $linesHeaderContentClass = [
            "<?php\n",
            "namespace App\Repositories;\n",
            "use App\Repositories\Interfaces\\{$repositoryNameInterface};\n",
            "use App\Repositories\ModelRepository;\n",
        ];

        if (isset($this->classModelName)) {
            array_push($linesHeaderContentClass, "use App\Models\\{$this->classModelName};\n");
        }


        $linesBodyContentClass = [

            "class {$this->classRepositoryName} extends ModelRepository implements {$repositoryNameInterface}",
            "{",
            "\t/**",
            "\t*",
            "\t*",
            "\t* @param {$this->argumentRepositoryName} {$this->generateNameRepositoryClassModel($this->argumentRepositoryName)}",
            "\t*/",
            "\tpublic function __construct({$this->argumentRepositoryName} \${$this->generateNameRepositoryClassModel($this->argumentRepositoryName)})",
            "\t{",
            "\t\tparent::__construct(\${$this->generateNameRepositoryClassModel($this->argumentRepositoryName)});",
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


    private function commandResult()
    {
        $commandResult = "Not was possible create the Class Repository";

        if ($this->createRepositoryWasSuccessfully) {

            $commandResult = "Class Repository was created successfully!";
            $this->info($commandResult);
        } else {
            $this->error($commandResult);
        }
    }

    private function repositoryFileExists()
    {
        $repositoryFileFullPath =
            $this->generateRepositoryFileFullPath($this->classRepositoryName);

        $repositoryExists = $this->filesystem->exists($repositoryFileFullPath);

        return $repositoryExists;
    }

    public function generateRepositoryFileFullPath()
    {
        $repositoryFileFullPath = "{$this::$pathRepositories}/{$this->classRepositoryName}.php";
        return $repositoryFileFullPath;
    }

    private function repositoryModelFileExists()
    {
        $createModelRepository =
            new CreateRepositoryModel($this->filesystem);

        $repositoryModelExists = $createModelRepository->repositoryModelFileExists();

        return $repositoryModelExists;
    }

    public function repositoryModelInterfaceExists()
    {
        $repositoryModelInterfaceExists = new CreateModelRepositoryInterface(
            $this->filesystem
        );


        $repositoryModelInterfaceExists = $repositoryModelInterfaceExists->repositoryModelInterfaceExists();
        return $repositoryModelInterfaceExists;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateRepositoryModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repositoryModel {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var String $classRepositoryModelName classRepositoryModelName */
    public $classRepositoryModelName;

    /** @var String $classModelName classModelName */
    private $classModelName;

    /** @var Filesystem $filesystem filesystem */
    private $filesystem;

    /** @var String $pathRepositories pathRepositories */
    public static $pathRepositories = './app/Repositories';

    /** @var Boolean $createRepositoryModelWasSuccessfully createRepositoryModelWasSuccessfully */
    private  $createRepositoryModelWasSuccessfully;

    /** @var Boolean $createResourceMethods createResourceMethods */
    private  $createResourceMethods;

    /** @var Exception $exception exception */
    private  $exception;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->createRepositoryModelWasSuccessfully = false;

        $this->filesystem = $filesystem;

        $this->classRepositoryModelName = 'RepositoryModel';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if ($this->repositoryModelFileExists()) {
            $this->warn(__('files.alreadyExists', [
                'file' => $this->classRepositoryModelName
            ]));
        } else {
            $this->generateRepositoryModelFile();
        }
    }

    private function generateClassContent()
    {

        $linesHeaderContentClass = [
            "<?php\n",
            "namespace App\Repositories;\n",
            "use Illuminate\Database\Eloquent\Model;\n",
            "use App\Repositories\Interfaces\RepositoryEloquentInterface as InterfacesRepositoryEloquentInterface;\n",

        ];

        if ($this->createResourceMethods) {

            array_push($linesHeaderContentClass, "use Illuminate\Http\Resources\Json\Resource;\n");
            array_push($linesHeaderContentClass, "use Illuminate\Http\Resources\Json\ResourceCollection;\n");
        }

        if (isset($this->classModelName)) {
            array_push($linesHeaderContentClass, "use App\Models\\{$this->classModelName};\n");
        }




        $linesBodyContentClass = [
            "class {$this->classRepositoryModelName} extends InterfacesRepositoryEloquentInterface",
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



            "\t/**",
            "\t* @param array \$attributes",
            "\t* @return Model",
            "\t*/",
            "\tpublic function create(array \$attributes)",
            "\t{",
            "\t\treturn \$this->model->create(\$attributes);",
            "\t}",

            "\t/**",
            "\t* @param Model \$model",
            "\t* @return void",
            "\t*/",
            "\tpublic function delete(\$model)",
            "\t{",
            "\t\treturn \$this->model->delete();",
            "\t}",

            "\t/**",
            "\t* @param mixed \$id",
            "\t* @return Model",
            "\t*/",
            "\tpublic function findById(\$id)",
            "\t{",
            "\t\treturn \$this->model->find(\$id);",
            "\t}",



            "\t/**",
            "\t* ",
            "\t* @return Collection",
            "\t*/",
            "\tpublic function findAll()",
            "\t{",
            "\t\treturn \$this->model->findAll();",
            "\t}",


            "\t/**",
            "\t* @param Model \$model",
            "\t* @param array \$attributes",
            "\t* @return Model",
            "\t*/",
            "\tpublic function update(array \$attributes)",
            "\t{",
            "\t\treturn \$this->model->update(\$attributes);",
            "\t}",


        ];


        $lineGetResourceModel = [
            "\t/**",
            "\t* @param Model \$model",
            "\t* @return Resource",
            "\t*/",
            "\tpublic function getResourceModel(Model \$model)",
            "\t{",
            "\t\treturn new Resource(\$model);",
            "\t}",
            "\n",
        ];

        $lineGetResourceCollectionModel = [
            "\t/**",
            "\t* ",
            "\t* @return ResourceCollection",
            "\t*/",
            "\tpublic function getResourceCollectionModel()",
            "\t{",
            "\t\treturn new ResourceCollection(\$this->model->all());",
            "\t}",
            "\n",
        ];

        if ($this->createResourceMethods) {

            $linesBodyContentClass = array_merge(
                $linesBodyContentClass,
                $lineGetResourceModel,
                $lineGetResourceCollectionModel
            );
        }
        array_push($linesBodyContentClass, "}\n");

        $linesContentClass = array_merge($linesHeaderContentClass, $linesBodyContentClass);

        $classContent = "";
        foreach ($linesContentClass as  $line) {
            $classContent .= "{$line}\n";
        }

        return $classContent;
    }

    private function generateRepositoryModelFile()
    {
        $this->createResourceMethods = ($this->option('resource'));


        if (!$this->repositoryEloquentInterfaceFileExists()) {

            $createRepositoryEloquentInterfaceCommand = "make:repositoryEloquentInterface";

            if ($this->createResourceMethods) {

                $this->callSilent(
                    $createRepositoryEloquentInterfaceCommand,
                    ['--resource' => true]
                );
            } else {

                $this->callSilent($createRepositoryEloquentInterfaceCommand);
            }
        }

        $this->makeDirectoryRepositories();

        $this->makeRepositoryClass();

        $this->commandResult();
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
    private function generateRepositoryModelFileFullPath()
    {
        $repositoryModelFileFullPath = "{$this::$pathRepositories}/{$this->classRepositoryModelName}.php";
        return $repositoryModelFileFullPath;
    }



    private function makeRepositoryClass()
    {
        $repositoryModelFileFullPath =
            $this->generateRepositoryModelFileFullPath();
        try {

            $this->filesystem->put($repositoryModelFileFullPath, $this->generateClassContent());

            $this->createRepositoryModelWasSuccessfully = true;
        } catch (\Exception $exception) {
            $this->exception = $exception;
            $this->logErrorFromException();
        }
    }

    private function logErrorFromException()
    {
        logger(
            get_class($this),
            [
                'exception' => $this->exception
            ]
        );
    }


    private function commandResult()
    {

        if ($this->createRepositoryModelWasSuccessfully) {

            $commandResult = __('files.createdSuccessfully', [
                'file' => $this->classRepositoryModelName
            ]);
            $this->info($commandResult);
        }
    }

    private function repositoryEloquentInterfaceFileExists()
    {
        $createRepositoryEloquentInterface =
            new CreateRepositoryEloquentInterface($this->filesystem);

        $repositoryEloquentInterfacePath = $createRepositoryEloquentInterface::$pathRepositories;

        $repositoryEloquentInterfaceFile =
            "{$repositoryEloquentInterfacePath}/{$createRepositoryEloquentInterface->interfaceRepositoryName}";

        $repositoryEloquentInterfaceFileExists = $this->filesystem->exists($repositoryEloquentInterfaceFile);

        return $repositoryEloquentInterfaceFileExists;
    }

    public function repositoryModelFileExists()
    {

        $repositoryModelFileFullPath =
            $this->generateRepositoryModelFileFullPath($this->classRepositoryModelName);

        $repositoryModelExists = $this->filesystem->exists($repositoryModelFileFullPath);
        return $repositoryModelExists;
    }
}

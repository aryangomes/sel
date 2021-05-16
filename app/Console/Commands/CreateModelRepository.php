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
    public $classRepositoryName;

    /** @var String $classModelName classModelName */
    private $classModelName;

    /** @var Filesystem $filesystem filesystem */
    private $filesystem;

    /** @var String $classRepositoryName classRepositoryName */
    private static $pathRepositories = './app/Repositories';

    /** @var Boolean $createModelRepositoryWasSuccessfully createModelRepositoryWasSuccessfully */
    private  $createModelRepositoryWasSuccessfully;

    /** @var Boolean $createResourceMethods createResourceMethods */
    private  $createResourceMethods;

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
        $this->createResourceMethods = ($this->option('resource') != null);

        if (!$this->repositoryEloquentInterfaceFileExists()) {
            $createRepositoryEloquentInterfaceCommand = "make:repositoryEloquentInterface";
            if ($this->createResourceMethods) {
                $createRepositoryEloquentInterfaceCommand .= " --{resource}";
            }

            $this->callSilent($createRepositoryEloquentInterfaceCommand);
        }


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
            "use App\Repositories\Interfaces\RepositoryEloquentInterface as InterfacesRepositoryEloquentInterface;\n",

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
            "\tpublic function getResourceModel(Model \$model);",
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
            "\tpublic function getResourceCollectionModel();",
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
            $this->warn(__('files.alreadyExists', [
                'file' => $this->classRepositoryName
            ]));
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

        if ($this->createModelRepositoryWasSuccessfully) {

            $resultOfCommand = __('files.createdSuccessfully', [
                'file' => $this->classRepositoryName
            ]);
            $this->info($resultOfCommand);
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
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateRepositoryEloquentInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repositoryEloquentInterface {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var String $interfaceRepositoryName interfaceRepositoryName */
    public $interfaceRepositoryName;

    /** @var String $classModelName classModelName */
    private $classModelName;

    /** @var Filesystem $filesystem filesystem */
    private $filesystem;

    /** @var String $pathRepositories pathRepositories */
    public static $pathRepositories = './app/Repositories/Interfaces';

    /** @var Boolean $createRepositoryEloquentInterfaceWasSuccessfully createRepositoryEloquentInterfaceWasSuccessfully */
    private  $createRepositoryEloquentInterfaceWasSuccessfully;

    /** @var Boolean $createResourceMethods createResourceMethods */
    private  $createResourceMethods;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->createRepositoryEloquentInterfaceWasSuccessfully = false;

        $this->filesystem = $filesystem;

        $this->interfaceRepositoryName = 'RepositoryEloquentInterfaceTeste';

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

        $this->makeDirectoryRepositories();

        $this->makeRepositoryClass();

        $this->resultOfCommand();
    }



    private function generateClassContent()
    {

        $linesHeaderContentClass = [
            "<?php\n",
            "namespace App\Repositories\Interfaces;\n",
            "use Illuminate\Database\Eloquent\Model;\n",
        ];

        $linesBodyContentClass = [
            "interface {$this->interfaceRepositoryName}",
            "{",
            "\t/**",
            "\t* @param array \$attributes",
            "\t* @return Model",
            "\t*/",
            "\tpublic function create(array \$attributes);",
            "\n",

            "\t/**",
            "\t* @param Model \$model",
            "\t* @return void",
            "\t*/",
            "\tpublic function delete(Model \$model);",
            "\n",

            "\t/**",
            "\t* @param mixed \$id",
            "\t* @return Model",
            "\t*/",
            "\tpublic function findById(\$id);",
            "\n",

            "\t/**",
            "\t* ",
            "\t* @return Collection",
            "\t*/",
            "\tpublic function findAll();",
            "\n",

            "\t/**",
            "\t* @param Model \$model",
            "\t* @param array \$attributes",
            "\t* @return Model",
            "\t*/",
            "\tpublic function update(array \$attributes, Model \$model);",
            "\n",


        ];

        $lineGetResourceModel = [
            "\t/**",
            "\t* @param Model \$model",
            "\t* @return Resource",
            "\t*/",
            "\tpublic function getResourceModel(Model \$model);",
            "\n",
        ];

        $lineGetResourceCollectionModel = [
            "\t/**",
            "\t* ",
            "\t* @return ResourceCollection",
            "\t*/",
            "\tpublic function getResourceCollectionModel();",
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
        $fullPathOfFileRepositoryClass = "{$this::$pathRepositories}/{$this->interfaceRepositoryName}.php";

        $repositoryClassExists = $this->filesystem->exists($fullPathOfFileRepositoryClass);
        if (!$repositoryClassExists) {
            try {

                $this->filesystem->put($fullPathOfFileRepositoryClass, $this->generateClassContent());

                $this->createRepositoryEloquentInterfaceWasSuccessfully = true;
            } catch (\Exception $exception) {

                $this->logErrorFromException($exception);
            }
        } else {
            $this->warn(__('files.alreadyExists', [
                'file' => $this->interfaceRepositoryName
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

        if ($this->createRepositoryEloquentInterfaceWasSuccessfully) {

            $resultOfCommand = __('files.createdSuccessfully', [
                'file' => $this->interfaceRepositoryName
            ]);
            $this->info($resultOfCommand);
        }
    }
}

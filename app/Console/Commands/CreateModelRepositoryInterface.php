<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateModelRepositoryInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:modelNameRepositoryInterface {modelNameRepositoryInterface}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var String $modelNameRepositoryInterface modelNameRepositoryInterface */
    private $modelNameRepositoryInterface;


    /** @var Filesystem $filesystem filesystem */
    private $filesystem;

    /** @var String $pathRepositoriesInterfaces pathRepositoriesInterfaces */
    private static $pathRepositoriesInterfaces = './app/Repositories/Interfaces';

    /** @var Boolean $createModelRepositoryInterfaceWasSuccessfully createModelRepositoryInterfaceWasSuccessfully */
    private  $createModelRepositoryInterfaceWasSuccessfully;

    /** @var Exception $exception exception */
    private  $exception;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        $this->createModelRepositoryInterfaceWasSuccessfully = false;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->generateModelNameRepositoryInterface($this->argument('modelNameRepositoryInterface'));

        if ($this->repositoryModelInterfaceExists()) {
            $this->warn(__('files.alreadyExists', [
                'file' => $this->modelNameRepositoryInterface
            ]));
        } else {
            $this->generateModelRepositoryInterfaceFile();
        }
    }

    private function generateModelRepositoryInterfaceFile()
    {
        $this->makeDirectoryRepositories();

        if (!$this->repositoryEloquentInterfaceFileExists()) {

            $createRepositoryEloquentInterfaceCommand = "make:repositoryEloquentInterface";

            $this->callSilent($createRepositoryEloquentInterfaceCommand);
        }

        $this->makeModelRepositoryInterface();

        $this->commandResult();
    }

    private function generateModelNameRepositoryInterface($modelNameRepositoryInterface)
    {
        $modelNameRepositoryInterfaceGenerated =  ucfirst("{$modelNameRepositoryInterface}RepositoryInterface");
        $this->modelNameRepositoryInterface = $modelNameRepositoryInterfaceGenerated;
    }

    private function generateModelRepositoryInterfaceContent()
    {

        $linesHeaderContentClass = [
            "<?php\n",
            "namespace App\Repositories\Interfaces;\n",

        ];

        $linesBodyContentClass = [
            "interface {$this->modelNameRepositoryInterface} extends RepositoryEloquentInterface",
            "{",
            "}\n"
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
        $directoryRepositoriesExists = $this->filesystem->exists($this::$pathRepositoriesInterfaces);
        if (!$directoryRepositoriesExists) {
            try {
                $this->filesystem->makeDirectory($this::$pathRepositoriesInterfaces);
            } catch (\Exception $exception) {
                $this->logErrorFromException($exception);
            }
        }
    }

    private function generateFullPathOfFileModelRepositoryInterface()
    {
        $fullPathOfFileModelRepositoryInterface =
            "{$this::$pathRepositoriesInterfaces}/{$this->modelNameRepositoryInterface}.php";
        return $fullPathOfFileModelRepositoryInterface;
    }

    private function makeModelRepositoryInterface()
    {

        try {

            $this->filesystem->put(
                $this->generateFullPathOfFileModelRepositoryInterface(),
                $this->generateModelRepositoryInterfaceContent()
            );

            $this->createModelRepositoryInterfaceWasSuccessfully = true;
        } catch (\Exception $exception) {
            $this->exception = $exception;
            $this->logErrorFromException();
        }
    }

    private function repositoryEloquentInterfaceFileExists()
    {

        $createRepositoryEloquentInterface = new CreateRepositoryEloquentInterface($this->filesystem);
        $repositoryEloquentInterfaceFileFullPath =
            $createRepositoryEloquentInterface->generateRepositoryEloquentInterfaceFileFullPath();

        $repositoryEloquentInterfaceFileExists = $this->filesystem->exists($repositoryEloquentInterfaceFileFullPath);

        return $repositoryEloquentInterfaceFileExists;
    }


    public function repositoryModelInterfaceExists()
    {
        $fullPathOfFileModelRepositoryInterface =
            $this->generateFullPathOfFileModelRepositoryInterface($this->modelNameRepositoryInterface);

        $repositoryModelInterfaceExists = $this->filesystem->exists($fullPathOfFileModelRepositoryInterface);
        return $repositoryModelInterfaceExists;
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

        if ($this->createModelRepositoryInterfaceWasSuccessfully) {

            $commandResult = __('files.createdSuccessfully', [
                'file' => $this->modelNameRepositoryInterface
            ]);
            $this->info($commandResult);
        } else {
            $this->error($this->exception);
        }
    }
}

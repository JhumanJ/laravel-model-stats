<?php


namespace Jhumanj\LaravelModelStats\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Tinker\ClassAliasAutoloader;
use Psy\Configuration;
use Psy\ExecutionLoopClosure;
use Psy\Shell;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Taken from https://github.com/spatie/laravel-web-tinker/blob/master/src/Tinker.php
 *
 * Class Tinker
 * @package Jhumanj\LaravelModelStats\Services
 */
class Tinker
{

    /** @var \Symfony\Component\Console\Output\BufferedOutput */
    protected BufferedOutput $output;

    /** @var \Psy\Shell */
    protected Shell $shell;

    public function __construct()
    {
        $this->output = new BufferedOutput();

        $this->shell = $this->createShell($this->output);
    }

    public function execute(string $phpCode): string
    {
        $phpCode = $this->removeComments($phpCode);

        $this->shell->addInput($phpCode);

        $closure = new ExecutionLoopClosure($this->shell);

        $closure->execute();

        $resultVars = $this->shell->getScopeVariables();

        // Detect db write exception
        if (! $this->lastExecSuccess() && isset($resultVars['_e'])) {
            $lastException = $resultVars['_e'];
            if (($lastException instanceof QueryException)
                && Str::of($lastException->getMessage())
                      ->contains("SQLSTATE[42501]: Insufficient privilege")) {
                return "For safety reasons, you can only query data with ModelStats. Write operations are forbidden.";
            }
        }

        // Make sure we have a result var
        if ($this->lastExecSuccess() && is_null($this->getCustomCodeResult())) {
            return "Error, 'result' variable missing or empty.";
        }

        return $this->cleanOutput($this->output->fetch());
    }

    /**
     * Get the content of result variable
     */
    public function getCustomCodeResult()
    {
        if (! $this->lastExecSuccess()) {
            return null;
        }

        try {
            $result = $this->shell->getScopeVariable('result');
        } catch (Exception $exception) {
            ray($exception);

            return null;
        }
        if ($result && ! empty($result)) {
            return $result;
        }

        return null;
    }

    /**
     * Check if last execution worked without exceptions
     */
    public function lastExecSuccess(): bool
    {
        return $this->shell->getLastExecSuccess();
    }

    /**
     * Prevents unwanted database modifications by enabling creating and using a readonly connection.
     */
    public function setConnection(): static
    {
        DB::setDefaultConnection(config('model-stats.query_database_connection'));

        return $this;
    }

    /**
     * Inject chart dates (if needed) in the shell.
     */
    public function injectDates(Carbon $dateFrom, Carbon $dateTo): static
    {
        $this->shell->setScopeVariables([
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);

        return $this;
    }

    protected function createShell(BufferedOutput $output): Shell
    {
        $config = new Configuration([
            'updateCheck' => 'never',
        ]);

        $config->setHistoryFile(defined('PHP_WINDOWS_VERSION_BUILD') ? 'null' : '/dev/null');

        $config->getPresenter()->addCasters([
            Collection::class => 'Laravel\Tinker\TinkerCaster::castCollection',
            Model::class => 'Laravel\Tinker\TinkerCaster::castModel',
            Application::class => 'Laravel\Tinker\TinkerCaster::castApplication',
        ]);

        $shell = new Shell($config);

        $shell->setOutput($output);

        $composerClassMap = base_path('vendor/composer/autoload_classmap.php');

        if (file_exists($composerClassMap)) {
            ClassAliasAutoloader::register($shell, $composerClassMap);
        }

        return $shell;
    }

    public function removeComments(string $code): string
    {
        $tokens = collect(token_get_all("<?php\n".$code.'?>'));

        return $tokens->reduce(function ($carry, $token) {
            if (is_string($token)) {
                return $carry.$token;
            }

            $text = $this->ignoreCommentsAndPhpTags($token);

            return $carry.$text;
        }, '');
    }

    protected function ignoreCommentsAndPhpTags(array $token)
    {
        [$id, $text] = $token;

        if ($id === T_COMMENT) {
            return '';
        }
        if ($id === T_DOC_COMMENT) {
            return '';
        }
        if ($id === T_OPEN_TAG) {
            return '';
        }
        if ($id === T_CLOSE_TAG) {
            return '';
        }

        return $text;
    }

    protected function cleanOutput(string $output): string
    {
        $output = preg_replace('/(?s)(<aside.*?<\/aside>)|Exit: {2}Ctrl\+D/ms', '$2', $output);

        return trim($output);
    }
}

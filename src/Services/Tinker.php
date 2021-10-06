<?php


namespace Jhumanj\LaravelModelStats\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Tinker\ClassAliasAutoloader;
use Psy\Configuration;
use Psy\ExecutionLoopClosure;
use Psy\Shell;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Support\Facades\Config;

/**
 * Taken from https://github.com/spatie/laravel-web-tinker/blob/master/src/Tinker.php
 *
 * Class Tinker
 * @package Jhumanj\LaravelModelStats\Services
 */
class Tinker
{
    const FAKE_WRITE_HOST = 'database_write_not_allowed_with_model_stats';

    /** @var \Symfony\Component\Console\Output\BufferedOutput */
    protected $output;

    /** @var \Psy\Shell */
    protected $shell;

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
        if (!$this->lastExecSuccess() && isset($resultVars['_e'])) {
            $lastException = $resultVars['_e'];
            if (get_class($lastException) === 'Illuminate\Database\QueryException') {
                if (Str::of($lastException->getMessage())->contains(self::FAKE_WRITE_HOST)) {
                    return "For safety reasons, you can only query data with ModelStats. Write operations are forbidden.";
                }
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
    public function getCustomCodeResult() {
        if (!$this->lastExecSuccess()) {
            return null;
        }

        try {
            $result = $this->shell->getScopeVariable('result');
        } catch (\Exception $exception) {
            ray($exception);
            return null;
        }
        if ($result && !empty($result) ){
            return $result;
        }

        return null;
    }

    /**
     * Check if last execution worked without exceptions
     */
    public function lastExecSuccess() {
        return $this->shell->getLastExecSuccess();
    }

    /**
     * Prevents unwanted database modifications by enabling creating and using a readonly connection.
     */
    public function readonly() {
        $defaultConnection = config('database.default');
        $databaseConnection = Config::get('database.connections.'.$defaultConnection);
        $host = $databaseConnection['host'];
        unset($databaseConnection['host']);
        $databaseConnection['read'] = [
            'host' => $host
        ];
        $databaseConnection['write'] = [
            'host' => self::FAKE_WRITE_HOST
        ];
        Config::set('database.connections.readonly',$databaseConnection);
        DB::setDefaultConnection('readonly');
        return $this;
    }

    /**
     * Inject chart dates (if needed) in the shell.
     */
    public function injectDates(Carbon $dateFrom, Carbon $dateTo)
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
            'configFile' => config('web-tinker.config_file') !== null ? base_path().'/'.config('web-tinker.config_file') : null,
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
        $output = preg_replace('/(?s)(<aside.*?<\/aside>)|Exit:  Ctrl\+D/ms', '$2', $output);

        return trim($output);
    }
}

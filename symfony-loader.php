<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyLoader
{
    protected static $instance;

    protected static $container;

    public static function load()
    {
        if (is_null(static::$instance))
        {
            static::$instance = new self;
        }
        return static::$instance;
    }

    public static function determineAbsolutePath($directory = false)
    {
        $directory = is_dir($directory) ? $directory : sprintf('%s../%s', get_home_path(), $directory);
        return realpath($directory);
    }

    public function request()
    {
        $sfKernel = $this->symfony('kernel');

        $sfRequest = Request::createFromGlobals();
        $sfResponse = $sfKernel->handle($sfRequest);
        $sfResponse->send();

        $sfKernel->terminate($sfRequest, $sfResponse);
    }

    public function __construct()
    {
        $this->_load('dev', false);
    }

    private function _load($environment, $debug)
    {
        $loader = require_once SYMFONY_ABSPATH.'/app/bootstrap.php.cache';

        // Load application kernel
        require_once SYMFONY_ABSPATH.'/app/AppKernel.php';

        $sfKernel = new AppKernel($environment, $debug);
        $sfKernel->loadClassCache();
        $sfKernel->boot();

        // Add Symfony container as a global variable to be used in Wordpress
        $sfContainer = $sfKernel->getContainer();

        if (true === $sfContainer->getParameter('kernel.debug', false)) {
            Debug::enable();
        }

        $sfContainer->enterScope('request');

        $this->symfony($sfContainer);
    }

    /**
     * Retrieves or sets the Symfony Dependency Injection container
     *
     * @param ContainerInterface|string $id
     *
     * @return mixed
     */
    public function symfony($id)
    {
        if ($id instanceof ContainerInterface) {
            static::$container = $id;
            return;
        }

        return static::$container->get($id);
    }

    function __get($id)
    {
        return $this->symfony($id);
    }
}
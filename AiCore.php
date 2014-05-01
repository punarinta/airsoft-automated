<?php

/**
 * On any page where you need to have access to AI features just init this class like
 * $ai = new AiCore;
 */

use AiCore\Loader;
use AiCore\Model\Db;

class AiCore
{
    protected $db = null;
    protected $config = null;
    protected $services = array();
    protected $aPathVar = '';    // form APath implementation

    /**
     * Constructor. See usage manual above.
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $config = require_once (__DIR__ . '/config/config.php');

        // setup options: defaults may be overridden at init
        $this->config = array_merge($config, $options);

        // init my custom class loader
        require_once(__DIR__ . '/src/Loader.php');

        if ($this->config['db'])
        {
            Loader::load('Model\Db');
            $this->db = new Db($this->config['db']);
        }

        global $globalAiCore;
        $globalAiCore = $this;
    }

    /**
     * Provides access to service. Services operate on entities.
     *
     * @param $serviceName
     * @return mixed
     */
    public function getService($serviceName)
    {
        if (!isset ($this->services[$serviceName]))
        {
            Loader::load('Service\Generic');
            Loader::load('Service\\' . $serviceName);

            $serviceClassName = '\AiCore\Service\\' . $serviceName;
            $service = new $serviceClassName;
            $service->setDb($this->db);
            $this->services[$serviceName] = $service;
        }

        return $this->services[$serviceName];
    }

    /**
     * Returns internal DB hook
     *
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Return the config. Support APath.
     *
     * @param string $varPath
     * @param bool $skipNulls
     * @param null $root
     * @return array|null
     * @throws Exception
     */
    public function getConfig($varPath = '', $skipNulls = true, $root = null)
    {
        if (!$root)
        {
            $root = $this->config;
            $this->aPathVar = $varPath;
        }
        $root = (array) $root;

        $varNames = (array) explode('/', $varPath, 2);

        if (empty($varNames[0])) return $root;

        if (count($varNames) == 2)
        {
            if (isset ($root[$varNames[0]])) return $this->getConfig($varNames[1], $skipNulls, $root[$varNames[0]]);
            else if ($skipNulls)             return null;
        }
        else
        {
            if (isset ($root[$varNames[0]])) return $root[$varNames[0]];
            else if ($skipNulls)             return null;
        }

        // in the default case just report an error
        throw new \Exception('Variable not found within [' . $this->aPathVar . ']');
    }
}

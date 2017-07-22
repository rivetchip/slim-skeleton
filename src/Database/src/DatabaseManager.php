<?php

namespace Database;

use Database\Interfaces\ConnectionResolverInterface;

use PDO;
use InvalidArgumentException;


class DatabaseManager implements ConnectionResolverInterface
{

    /**
     * Configurations container
     */
    protected $configurations = [];

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = [];


    /**
     * Create a new database manager instance.
     */
    public function __construct()
    {
        // bootstrap ConnectionRetrieveResolver so it is ready for usage anywhere

        ConnectionRetrieveTrait::setConnectionResolver($this);
    }

    /**
     * Get default application settings
     * 
     * @return array
     */
    protected static function getDefaultSettings()
    {
        return [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => '',
            'username'  => '',
            'password'  => '',
            'charset'   => 'utf8',
            'prefix'    => '',
            'querylog' => false,
            // 'strict'   => bool
            // 'options'  => []
        ];
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    protected static function getDefaultConnectionName()
    {
        return 'default';
    }

    /**
     * Register a connection with the manager.
     * 
     * @param array  $settings 
     * @param string $name
     */
    public function addConnection( array $settings, $name = null )
    {
		$name = $name ?: static::getDefaultConnectionName();
		
        $this->configurations[$name] = array_merge(static::getDefaultSettings(), $settings);

        $this->connections[$name] = $this->makeConnection($name);
    }

    /**
     * Open a connection and simply store its instance
     * 
     * @param  string $name
     */
    protected function makeConnection( $name = null )
    {
        $name = $name ?: static::getDefaultConnectionName();

        $config = $this->configurations[$name];

        // create the connection object // idea: factory switch 'driver'

        $pdo = ( new Connector )->connect($config);

        $connection = new Connection($pdo, $config['database'], $config['prefix'], $config['querylog']);

        return $connection;
    }

    /**
     * Get a database connection instance.
     *
     * @param  string  $name
     * @return \Fox\Database\Connection
     */
    public function connection( $name = null )
    {
        $name = $name ?: static::getDefaultConnectionName();

        return $this->connections[$name];
    }

    /**
     * Return all of the created connections.
     *
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }


}

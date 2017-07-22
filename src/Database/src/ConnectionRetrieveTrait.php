<?php

namespace Database;

use Database\Interfaces\ConnectionResolverInterface as ConnectionResolver;


trait ConnectionRetrieveTrait
{

    /**
     * connection name for the model
     * @var string
     */
    protected $connectionName;

    /**
     * connection resolver instance
     * @var \Fox\Database\Interfaces\ConnectionResolverInterface
     */
    protected static $connectionResolver;


    /**
     * Create a new instance
     * 
     * @return static
     */
    public static function newInstance()
    {
        return ( new static );
    }

    /**
     * Start the model with a given connection
     *
     * @param  string  $connection
     * @return static
     */
    public static function on( $name = null )
    {
        // First we will just create a fresh instance of this model, and then we can
        // set the connection on the model so that it is be used for the queries
        // we execute, as well as being set on each relationship we retrieve.

        $instance = static::newInstance();

        $instance->setConnectionName($name);

        return $instance;
    }

    /**
     * Get the database connection for the model.
     *
     * @return \Fox\Database\Connection
     */
    public function connection()
    {
        return static::resolveConnection($this->connectionName);
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connectionName;
    }

    /**
     * Set the connection associated with the model.
     *
     * @param  string  $name
     * @return $this
     */
    public function setConnectionName( $name )
    {
        $this->connectionName = $name;

        return $this;
    }

    /**
     * Resolve a connection instance.
     *
     * @param  string|null  $name
     * @return \Fox\Database\Connection
     */
    public static function resolveConnection( $name = null )
    {
        return static::$connectionResolver->connection($name);
    }

    /**
     * Get the connection resolver instance.
     *
     * @return \Fox\Database\Interfaces\ConnectionResolverInterface
     */
    public static function getConnectionResolver()
    {
        return static::$connectionResolver;
    }

    /**
     * Set the connection resolver instance.
     *
     * @param  \Fox\Database\Interfaces\ConnectionResolverInterface  $resolver
     */
    public static function setConnectionResolver( ConnectionResolver $resolver )
    {
        static::$connectionResolver = $resolver;
    }

    /**
     * Unset the connection resolver for models.
     */
    public static function unsetConnectionResolver()
    {
        static::$connectionResolver = null;
    }


}

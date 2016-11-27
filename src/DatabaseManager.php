<?php
namespace Germey\Support;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Connectors\ConnectionFactory;

class DatabaseManager implements ConnectionResolverInterface
{
	/**
	 * The application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $container;

	/**
	 * The active connection instances.
	 *
	 * @var array
	 */
	protected $connection;

	/**
	 * Create a new database manager instance.
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 * @param  \Illuminate\Database\Connectors\ConnectionFactory $factory
	 * @return void
	 */
	public function __construct($container, ConnectionFactory $factory)
	{
		$this->container = $container;
		$this->factory = $factory;
	}

	/**
	 * Get a database connection instance.
	 *
	 * @param  string $name
	 * @return \Illuminate\Database\Connection
	 */
	public function connection($name = null)
	{
		$connection = $this->makeConnection($name);
		$this->connection = $this->prepare($connection);
		return $this->connection;
	}


	/**
	 * Disconnect from the given database and remove from local cache.
	 *
	 * @param  string $name
	 * @return void
	 */
	public function purge()
	{
		$this->disconnect();
		unset($this->connection);
	}

	/**
	 * Disconnect from the given database.
	 *
	 * @param  string $name
	 * @return void
	 */
	public function disconnect()
	{
		$this->connection->disconnect();
	}

	/**
	 * Reconnect to the given database.
	 *
	 * @param  string $name
	 * @return \Illuminate\Database\Connection
	 */
	public function reconnect()
	{
		$this->disconnect();
		return $this->connection();
	}

	/**
	 * Make the database connection instance.
	 *
	 * @param  string $name
	 * @return \Illuminate\Database\Connection
	 */
	protected function makeConnection()
	{
		$config = $this->container->get('settings')['db'];
		return $this->factory->make($config);
	}

	/**
	 * Prepare the database connection instance.
	 *
	 * @param  \Illuminate\Database\Connection $connection
	 * @return \Illuminate\Database\Connection
	 */
	protected function prepare($connection)
	{
		$connection->setFetchMode($this->container->get('settings')['db']['fetch']);
		$connection->setReconnector(function ($connection) {
			$this->reconnect($connection->getName());
		});

		return $connection;
	}


	/**
	 * Get the default connection name.
	 *
	 * @return string
	 */
	public function getDefaultConnection()
	{
		// Not implemented
	}

	/**
	 * Set the default connection name.
	 *
	 * @param  string $name
	 * @return void
	 */
	public function setDefaultConnection($name)
	{
		// Not implemented
	}
}
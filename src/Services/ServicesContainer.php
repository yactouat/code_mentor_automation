<?php

namespace Udacity\Services;
 
abstract class ServicesContainer {

    /**
     * the id of the Singleton service
     *
     * @var integer|null
     */
    protected static ?int $id = null;
 
    /**
     * @var array
     * @access private
     * @static
     */
    protected static ?array $_instances;
 
    /**
    * class instanciator of a given service
    *
    * @param string $id the identifier of the service
    * @return self
    */
    public abstract static function getService(string $id): self;

    /**
     * resets the services that are kept in the container (mainly for testing purposes)
     *
     * @return void
     */
    public static function resetServices(): void {
        self::$_instances = [];
    }

}
<?php

namespace Udacity\Services;

/**
 * this service is responsible for informing its consumers about which mode the app' runs on (CLI, web, etc.)
 */
final class AppModeService extends ServicesContainer {

    /**
     * this prop holds the app' mode information
     *
     * @var string
     */
    private string $mode;

    /**
     * gets the app' mode (CLI, web, etc.)
     * 
     * the app's mode is set on app' startup
     * 
     * @return string
     * 
     * TODO test when app' mode is not set
     */
    public function getMode(): string {
        return $this->mode;
    }

    /**
    * @inheritDoc
    */
    public static function getService(string $id): self {
        if(empty(self::$_instances[$id])) {
            self::$_instances[$id] = new self();  
        }
        return self::$_instances[$id];
    }

    /**
     * sets the app' mode (CLI, web, etc.)
     * 
     * @return string
     */    
    public function setMode(string $mode): void {
        $this->mode = $mode;
    }
}
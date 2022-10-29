<?php declare(strict_types=1);

namespace Udacity\Automations;

/**
 * this is the parent class of all specifc processes run by the app
 */
abstract class Automation {

    /**
     * errors that happened during the automation execution
     *
     * @var array
     */
    protected $errors = [];

    public function getErrors(): array {
        return $this->errors;
    }

}
<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\ControllerInterface;

interface ResourceControllerInterface extends ControllerInterface {

    public function create(): string;

    public function index(): string;

    public function persist(): string;

}
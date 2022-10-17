<?php

namespace Udacity\Controllers\Resource;

use Udacity\Controllers\ControllerInterface;

interface ResourceControllerInterface extends ControllerInterface {

    public function create(): string;

    public function index(): string;

    public function persist(): string;

}
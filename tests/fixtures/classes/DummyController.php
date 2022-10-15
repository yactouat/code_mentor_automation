<?php

use Udacity\Controllers\Controller;
use Udacity\Controllers\ControllerInterface;

final class DummyController extends Controller implements ControllerInterface {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return '';
    }

}
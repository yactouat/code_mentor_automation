<?php

use Udacity\Apps\Web\Controllers\Controller;
use Udacity\Apps\Web\Controllers\ControllerInterface;

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
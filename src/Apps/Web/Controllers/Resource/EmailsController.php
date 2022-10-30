<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\Controller;
use Udacity\Automations\BehindStudentsEmailAutomation;
use Udacity\Models\EmailsModel;

/**
 * this controller is responsible for handling requests related to sending emails from the web
 */
final class EmailsController extends Controller implements ResourceControllerInterface {

    /**
     * {@inheritDoc}
     * 
     * ! not implemented
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     * 
     * - is auth protected
     * - checks if provided email type via query string exists otherwise 404's
     */    
    public function create(): string
    {
        $showLoginForm = $this->showLoginFormIfNotAuthed();
        $template = empty($showLoginForm) ? self::$notFoundTemplatePath : self::$loginTemplatePath;
        $statusCode = empty($showLoginForm) ? 404 : 401;
        if (
            empty($showLoginForm) 
            && !empty($_GET['type']) 
            && EmailsModel::validateEmailType($_GET['type'])
        ) {
            $template = 'emails/' . $_GET['type'] . '.create.html.twig';
            $statusCode = 200;
        }
        $this->setStatusCode($statusCode);
        return $this->getRenderer()->render($template);
    }
 
    /**
     * ! not implemented
     * 
     * @return string
     */    
    public function index(): string
    {
        return $this->getRenderer()->render(self::$notFoundTemplatePath);
    }

    /**
     * {@inheritDoc}
     * 
     */
    public function persist(): string
    {
        $showLoginForm = $this->showLoginFormIfNotAuthed();
        $template = empty($showLoginForm) ? self::$notFoundTemplatePath : self::$loginTemplatePath;
        $statusCode = empty($showLoginForm) ? 404 : 401;
        if ($statusCode !== 401) {
            $errors = [];
            $filesArr = [];
            $filesArr['sessreportcsv'] = '';
            if (!empty($_FILES['sessreportcsv']['name'])) {
                $filesArr['sessreportcsv'] = $_FILES['sessreportcsv']['name'];
            }
            $errors = EmailsModel::validateInputFields(array_merge(
                $_POST,
                $filesArr
            ));
            if (!isset($_POST['submit'])) {
                $errors[] = '⚠️ Please send a valid form using the `submit` button';
            }
            if (count($errors) > 0) {
                $this->setStatusCode(400);
                $invalidEmailType = empty($_POST['type']) || !EmailsModel::validateEmailType($_POST['type']);
                return $this->getRenderer()->render(
                    $invalidEmailType ? self::$homeTemplatePath : 'emails/' . $_POST['type'] . '.create.html.twig', 
                    $invalidEmailType ? [] :['errors' => $errors, 'userInput' => $_POST]
                );
            } else {
                $emails = new EmailsModel($_FILES['sessreportcsv']['name']);
                $csvDestFile = EmailsModel::$dataFolder . $emails->getSessReportCsv();
                move_uploaded_file(
                    $_FILES['sessreportcsv']['tmp_name'],
                    EmailsModel::$dataFolder . $emails->getSessReportCsv()
                );
                (new BehindStudentsEmailAutomation())
                    ->setNewLogger(empty($_ENV['isTesting']) ? '/var/www/data/logs/php/web_app.log' : 
                        '/var/www/tests/fixtures/logs/php/web_app.log'
                    )
                    ->run($csvDestFile, $_POST['language']);
                $statusCode = 200;
                $template = 'home.html.twig';
            }
        }
        $this->setStatusCode($statusCode);
        return $this->getRenderer()->render($template);
    }

}
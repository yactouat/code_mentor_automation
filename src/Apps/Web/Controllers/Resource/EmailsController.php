<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\Controller;
use Udacity\Models\EmailsModel;
use Udacity\Models\OnlineResourceModel;

/**
 * this controller is responsible for handling requests related to sending emails from the web
 */
final class EmailsController extends Controller implements ResourceControllerInterface {

    use FilesUploadsTrait;

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
     * takes in the input session report CSV and runs the automation to send the emails if validated or sets an errors array to be displayed to the user
     *
     * @return void
     */
    private function _processBatchEmailsInput(): void {
        $sessReportFileKey = 'sessreportcsv';
        $onlineResourcesFileKey = 'onlineresourcescsv';
        $errors = array_merge(
            EmailsModel::validateInputFields($this->getEmailsPayloadToValidate($sessReportFileKey)),
            OnlineResourceModel::validateInputFields($this->getEmailsPayloadToValidate($onlineResourcesFileKey))
        );
        if (!isset($_POST['submit'])) {
            $errors[] = '⚠️ Please send a valid form using the `submit` button';
        }
        if (count($errors) > 0) {
            $this->setStatusCode(400);
            $invalidEmailType = in_array(EmailsModel::getUnallowedEmailTypeErrorMess(), $errors);
            $this->setTwigData($invalidEmailType ? [] :['errors' => $errors, 'userInput' => $_POST]);
            $this->setTwigTemplate($invalidEmailType ? self::$homeTemplatePath : 'emails/' . $_POST['type'] . '.create.html.twig');
        } else {
            $automation = EmailsModel::getAutomationToRunFromEmailType($_POST['type']);
            $sessReportFileDest = EmailsModel::$dataFolder . $_FILES[$sessReportFileKey]['name'];
            $onlineResourcesFileDest = null;
            if (!empty($_FILES[$onlineResourcesFileKey]['name'])) {
                $onlineResourcesFileDest = EmailsModel::$dataFolder . $_FILES[$onlineResourcesFileKey]['name'];
                $this->uploadFile($onlineResourcesFileKey, $onlineResourcesFileDest);
            }
            $this->uploadFile($sessReportFileKey, $sessReportFileDest);
            (new ('Udacity\Automations\\' . $automation)())
                ->runFromCsv($sessReportFileDest, $_POST['language'], $onlineResourcesFileDest);
        }
    }

    /**
     * {@inheritDoc}
     * 
     * - is auth protected
     * - checks if provided email type via query string exists otherwise 404's
     */    
    public function create(): string
    {
        if (
            $this->isAuthed()
            && !empty($_GET['type']) 
            && EmailsModel::validateEmailType($_GET['type'])
        ) {
            $this->setTwigTemplate('emails/' . $_GET['type'] . '.create.html.twig');
            $this->setAuthedStatusCode(200);
        } else {
            $this->setTwigTemplate($this->getAuthedTwigTemplate(self::$notFoundTemplatePath));
            $this->setAuthedStatusCode(404);
        }
        return $this->getRenderer()->render($this->getTwigTemplate());
    }

    public function getEmailsPayloadToValidate(string $fileKey): array {
        return array_merge(
            $_POST,
            [$fileKey => $this->getUploadedFile($fileKey)]
        );
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
        // running the emails automation leads back to the homepage so we set that already, or we 401 if not authed
        $this->setTwigTemplate($this->getAuthedTwigTemplate('home.html.twig'));
        $this->setAuthedStatusCode(200);
        // running the automation
        if ($this->isAuthed()) {
            $this->_processBatchEmailsInput();
        }
        return $this->getRenderer()->render($this->getTwigTemplate(), $this->getTwigData());
    }

}
<?php declare(strict_types=1);

namespace Tests\Integration\Emails;

use Udacity\Csvs\CsvExtractor;
use Udacity\Emails\Emails;
use Udacity\Models\OnlineResourceModel;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Apps\Web\AuthenticateTrait;
use Tests\Integration\EnvLoaderTrait;
use Udacity\Exceptions\UserNotAuthedException;

final class EmailsTest extends TestCase
{

    use AuthenticateTrait;
    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
        $_POST = [];
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = "GET";
    }

    public function testGetBehindStudentsEmailTemplateInEnglish() {
        $expected = "Hey %s %s 👋 !<br>I am %s, your Udacity Fullstack Nanodegree Session Lead,<br>I've noticed that you did not attend last Connect session, is everything alright ?<br>If you need any help, please reach out to me on Slack; if you forgot how to access your Connect session, just go to your classroom at learn.udacity.com and you'll get the information you need in there !<br>I hope to see you at our next session 😉<br>Kind Regards,<br>%s";
        $actual = Emails::getBehindStudentEmailTemplate();
        $this->assertEquals($expected, $actual);
    }

    public function testGetBehindStudentsEmailTemplateInFrench() {
        $expected = "Bonjour %s %s 👋 !<br>Je suis %s, votre Udacity Fullstack Nanodegree Session Lead,<br>J'ai remarqué que vous n'avez pas pu venir à notre dernière session Connnect, est-ce que tout va bien ?<br>Si vous avez besoin d'aide, n'hésitez pas à me contacter sur Slack; si vous ne savez pas comment accéder à votre Connect session, rendez-vous sur learn.udacity.com, vous y trouverez toutes les informations nécessaires !<br>J'espère vous voir à notre prochaine session 😉<br>Bien Cordialement,<br>%s";
        $actual = Emails::getBehindStudentEmailTemplate("fr");
        $this->assertEquals($expected, $actual);
    }

    public function testGetBehindStudentsEmailFormattedInEnglish() {
        $this->authenticate();
        $expected = "Hey Test2 FirstName Test2 LastName 👋 !<br>I am Yacine, your Udacity Fullstack Nanodegree Session Lead,<br>I've noticed that you did not attend last Connect session, is everything alright ?<br>If you need any help, please reach out to me on Slack; if you forgot how to access your Connect session, just go to your classroom at learn.udacity.com and you'll get the information you need in there !<br>I hope to see you at our next session 😉<br>Kind Regards,<br>Yacine";
        $actual = Emails::getBehindStudentEmailFormatted("en", "Test2 FirstName", "Test2 LastName");
        $this->assertEquals($expected, $actual);
    }

    public function testGetBehindStudentsEmailFormattedInFrench() {
        $this->authenticate();
        $expected = "Bonjour Test2 FirstName Test2 LastName 👋 !<br>Je suis Yacine, votre Udacity Fullstack Nanodegree Session Lead,<br>J'ai remarqué que vous n'avez pas pu venir à notre dernière session Connnect, est-ce que tout va bien ?<br>Si vous avez besoin d'aide, n'hésitez pas à me contacter sur Slack; si vous ne savez pas comment accéder à votre Connect session, rendez-vous sur learn.udacity.com, vous y trouverez toutes les informations nécessaires !<br>J'espère vous voir à notre prochaine session 😉<br>Bien Cordialement,<br>Yacine";
        $actual = Emails::getBehindStudentEmailFormatted("fr", "Test2 FirstName", "Test2 LastName");
        $this->assertEquals($expected, $actual);
    }

    public function testTrainingEndingEmailTemplateInEnglish() {
        $expected = "Hey %s %s 👋 !<br>How are you ? I'm writing you this email as our Udacity training session will end soon,<br>I want to remind you that the Udacity team is behind you in your efforts and that your learning is also a collective one ! We're here to help 😉<br>So please keep asking questions to the session leads and to your peers on Slack !<br>There is still time to learn tons of stuff while you're enrolled in this training, so please enjoy it and, even better, you can still finish it 🚀<br>I hope to see you at our next session and I wish you all the best 😉<br>Kind Regards,<br>%s";
        $actual = Emails::getTrainingEndingEmailTemplate();
        $this->assertEquals($expected, $actual);
    }

    public function testTrainingEndingEmailTemplateInFrench() {
        $expected = "Bonjour %s %s 👋 !<br>Comment allez-vous ? Je vous écris cet email à l'occasion de la fin prochaine de notre session de formation,<br>Je veux vous rappeler que l'équipe de Udacity est derrière vous et vous soutient dans vos efforts et que votre apprentissage est aussi un apprentissage collectif !<br>Continuez à poser des questions aux session leads et à vos pairs sur Slack !<br>Il vous reste encore du temps pour apprendre énormément de choses tant que vous êtes inscrit dans cette formation et, encore mieux, vous pouvez encore terminer le parcours 🚀<br>J'espère vous voir à notre prochaine session et je vous souhaite le meilleur 😉<br>Bien Cordialement,<br>%s";
        $actual = Emails::getTrainingEndingEmailTemplate("fr");
        $this->assertEquals($expected, $actual);
    }

    public function testTrainingEndingEmailFormattedInEnglish() {
        $this->authenticate();
        $expected = "Hey Test2 FirstName Test2 LastName 👋 !<br>How are you ? I'm writing you this email as our Udacity training session will end soon,<br>I want to remind you that the Udacity team is behind you in your efforts and that your learning is also a collective one ! We're here to help 😉<br>So please keep asking questions to the session leads and to your peers on Slack !<br>There is still time to learn tons of stuff while you're enrolled in this training, so please enjoy it and, even better, you can still finish it 🚀<br>I hope to see you at our next session and I wish you all the best 😉<br>Kind Regards,<br>Yacine";
        $actual = Emails::getTrainingEndingEmailFormatted("en", "Test2 FirstName", "Test2 LastName");
        $this->assertEquals($expected, $actual);
    }

    public function testTrainingEndingEmailFormattedInFrench() {
        $this->authenticate();
        $expected = "Bonjour Test2 FirstName Test2 LastName 👋 !<br>Comment allez-vous ? Je vous écris cet email à l'occasion de la fin prochaine de notre session de formation,<br>Je veux vous rappeler que l'équipe de Udacity est derrière vous et vous soutient dans vos efforts et que votre apprentissage est aussi un apprentissage collectif !<br>Continuez à poser des questions aux session leads et à vos pairs sur Slack !<br>Il vous reste encore du temps pour apprendre énormément de choses tant que vous êtes inscrit dans cette formation et, encore mieux, vous pouvez encore terminer le parcours 🚀<br>J'espère vous voir à notre prochaine session et je vous souhaite le meilleur 😉<br>Bien Cordialement,<br>Yacine";
        $actual = Emails::getTrainingEndingEmailFormatted("fr", "Test2 FirstName", "Test2 LastName");
        $this->assertEquals($expected, $actual);
    }    

    public function testTrainingEndingEmailWithOnlineResourcesSpecifiedFormattedInEnglish() {
        $this->authenticate();
        $expected = "Hey Test2 FirstName Test2 LastName 👋 !<br>How are you ? I'm writing you this email as our Udacity training session will end soon,<br>I want to remind you that the Udacity team is behind you in your efforts and that your learning is also a collective one ! We're here to help 😉<br>So please keep asking questions to the session leads and to your peers on Slack !<br>There is still time to learn tons of stuff while you're enrolled in this training, so please enjoy it and, even better, you can still finish it 🚀<br>I hope to see you at our next session and I wish you all the best 😉<br>Kind Regards,<br>Yacine";
        $expected .="<div><h2>PS: Here are some resources to help you with journey:</h2><ul><li>foo - bar - some_link</li><li>foo2 - baz - some_other_link</li></ul></div>";
        $actual = Emails::getTrainingEndingEmailFormatted(
            "en", 
            "Test2 FirstName", 
            "Test2 LastName",
            CsvExtractor::getCSVData(
                '/var/www/tests/fixtures/csv/online-resources.csv',
                OnlineResourceModel::getCsvFields()
            )
        );
        $this->assertEquals($expected, $actual);
    }

    public function testTrainingEndingEmailWithOnlineResourcesSpecifiedFormattedInFrench() {
        $this->authenticate();
        $expected = "Bonjour Test2 FirstName Test2 LastName 👋 !<br>Comment allez-vous ? Je vous écris cet email à l'occasion de la fin prochaine de notre session de formation,<br>Je veux vous rappeler que l'équipe de Udacity est derrière vous et vous soutient dans vos efforts et que votre apprentissage est aussi un apprentissage collectif !<br>Continuez à poser des questions aux session leads et à vos pairs sur Slack !<br>Il vous reste encore du temps pour apprendre énormément de choses tant que vous êtes inscrit dans cette formation et, encore mieux, vous pouvez encore terminer le parcours 🚀<br>J'espère vous voir à notre prochaine session et je vous souhaite le meilleur 😉<br>Bien Cordialement,<br>Yacine";
        $expected .="<div><h2>PS: Voici quelques ressources pour vous aider dans votre apprentissage:</h2><ul><li>foo - bar - some_link</li><li>foo2 - baz - some_other_link</li></ul></div>";
        $actual = Emails::getTrainingEndingEmailFormatted(
            "fr", 
            "Test2 FirstName", 
            "Test2 LastName",
            CsvExtractor::getCSVData(
                '/var/www/tests/fixtures/csv/online-resources.csv',
                OnlineResourceModel::getCsvFields()
            )
        );
        $this->assertEquals($expected, $actual);
    }

    public function testGetBehindStudentEmailFormattedWithUnauthedUserThrowsException() {
        $this->expectException(UserNotAuthedException::class);
        $this->expectExceptionMessage('user not authenticated');
        $actual = Emails::getBehindStudentEmailFormatted("fr", "Test2 FirstName", "Test2 LastName");
    }
    
    public function testGetTrainingEndingEmailFormattedWithUnauthedUserThrowsException() {
        $this->expectException(UserNotAuthedException::class);
        $this->expectExceptionMessage('user not authenticated');
        $actual = Emails::getTrainingEndingEmailFormatted("fr", "Test2 FirstName", "Test2 LastName");
    }

}
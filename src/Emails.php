<?php

namespace App;

/**
 * class responsible for setting/getting emails templates
 * 
 * emails are currently available in:
 * - English
 * - French
 * 
 */
final class Emails
{
    
    /**
     * gets the template for students who are behind on their nanodegree track
     * 
     * this template contains placeholder values to fill on sending an actual personalized email
     *
     * @param string $language the language used in the template, defaults to English (`en`)
     * 
     * @return string the template in the language you have chosen
     */
    public static function getBehindStudentEmailTemplate(string $language = "en"): string {
        return match ($language) {
            "fr" => "Bonjour %s %s 👋 !<br>Je suis Yacine, votre Udacity Fullstack Nanodegree Session Lead,<br>J'ai remarqué que vous n'avez pas pu venir à notre dernière session Connnect, est-ce que tout va bien ?<br>Si vous avez besoin d'aide, n'hésitez pas à me contacter sur Slack; si vous ne savez pas comment accéder à votre Connect session, rendez-vous sur learn.udacity.com, vous y trouverez toutes les informations nécessaires !<br>J'espère vous voir à notre prochaine session 😉<br>Bien Cordialement,<br>Yacine",
            default => "Hey %s %s 👋 !<br>I am Yacine, your Udacity Fullstack Nanodegree Session Lead,<br>I've noticed that you did not attend last Connect session, is everything alright ?<br>If you need any help, please reach out to me on Slack; if you forgot how to access your Connect session, just go to your classroom at learn.udacity.com and you'll get the information you need in there !<br>I hope to see you at our next session 😉<br>Kind Regards,<br>Yacine",
        };
    }

    /**
     * gets the email text for a given student who is behind hers/his nanodegree track
     * 
     * using a the students behind template email, replaces the placeholder values of the template and gets you the text of the actual email
     *
     * @param string $language the language to use in the template
     * @param string $firstName the first name of the student to send the email to
     * @param string $lastName the last name of the student to send the email to
     * 
     * @return string the personalized student behind email
     */
    public static function getBehindStudentEmailFormatted(string $language, string $firstName, string $lastName): string {
        return sprintf(self::getBehindStudentEmailTemplate($language), $firstName, $lastName);
    }

    /**
     * gets the template for the email to send to students when the end of the training is near
     * 
     * this template contains placeholder values to fill on sending an actual personalized email
     *
     * @param string $language the language used in the template, defaults to English (`en`)
     * 
     * @return string the template in the language you have chosen
     */
    public static function getTrainingEndingTemplate(string $language = "en"): string {
        return match ($language) {
            "fr" => "Bonjour %s %s 👋 !<br>Comment allez-vous ? Je vous écris cet email à l'occasion de la fin prochaine de notre session de formation,<br>Je veux vous rappeler que l'équipe de Udacity est derrière vous et vous soutient dans vos efforts et que votre apprentissage est aussi un apprentissage collectif !<br>Continuez à poser des questions aux session leads et à vos pairs sur Slack !<br>Il vous reste encore du temps pour apprendre énormément de choses tant que vous êtes inscrit dans cette formation et, encore mieux, vous pouvez encore terminer le parcours 🚀<br>J'espère vous voir à notre prochaine session et je vous souhaite le meilleur 😉<br>Bien Cordialement,<br>Yacine",
            default => "Hey %s %s 👋 !<br>How are you ? I'm writing you this email as our Udacity training session will end soon,<br>I want to remind you that the Udacity team is behind you in your efforts and that your learning is also a collective one ! We're here to help 😉<br>So please keep asking questions to the session leads and to your peers on Slack !<br>There is still time to learn tons of stuff while you're enrolled in this training, so please enjoy it and, even better, you can still finish it 🚀<br>I hope to see you at our next session and I wish you all the best 😉<br>Kind Regards,<br>Yacine",
        };
    }

    /**
     * gets the email text for a given student to send when the end of the training is near
     * 
     * using a the students behind template email, replaces the placeholder values of the template and gets you the text of the actual email
     *
     * @param string $language the language to use in the template
     * @param string $firstName the first name of the student to send the email to
     * @param string $lastName the last name of the student to send the email to
     * 
     * @return string the personalized cheering up email
     */
    public static function getTrainingEndingFormatted(string $language, string $firstName, string $lastName): string {
        return sprintf(self::getTrainingEndingTemplate($language), $firstName, $lastName);
    }

}
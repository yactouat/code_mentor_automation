<?php

namespace App;

final class Emails
{

    public static function getBehindStudentEmailTemplate(string $language = "en"): string {
        return match ($language) {
            "fr" => "Bonjour %s %s 👋 !<br> Je suis Yacine, votre Udacity Fullstack Nanodegree Session Lead,<br> J'ai remarqué que vous n'avez pas pu venir à notre dernière session Connnect, est-ce que tout va bien ?<br> Si vous avez besoin d'aide, n'hésitez pas à me contacter sur Slack; si vous ne savez pas comment accéder à votre Connect session, rendez-vous sur learn.udacity.com, vous y trouverez toutes les informations nécessaires !<br> J'espère vous voir à notre prochaine session 😉<br> Bien Cordialement,<br> Yacine",
            default => "Hey %s %s 👋 !<br> I am Yacine, your Udacity Fullstack Nanodegree Session Lead,<br> I've noticed that you did not attend last Connect session, is everything alright ?<br> If you need any help, please reach out to me on Slack; if you forgot how to access your Connect session, just go to your classroom at learn.udacity.com and you'll get the intel in there !<br> I hope to see you at our next session 😉<br> Kind Regards,<br> Yacine",
        };
    }

    public static function getBehindStudentEmailFormatted(string $language, string $firstName, string $lastName): string {
        return sprintf(self::getBehindStudentEmailTemplate($language), $firstName, $lastName);
    }

}
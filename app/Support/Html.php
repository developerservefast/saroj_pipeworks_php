<?php

namespace App\Support;

class Html
{
    public static function clean(?string $html): ?string
    {
        if ($html === null || trim(strip_tags($html)) === '') {
            return null;
        }
        $c = \HTMLPurifier_Config::createDefault();
        $c->set('HTML.Allowed', 'p,br,strong,b,em,i,u,s,ul,ol,li,h2,h3,blockquote,a[href]');
        $c->set('Cache.DefinitionImpl', null);

        return (new \HTMLPurifier($c))->purify($html);
    }

    public static function display(?string $text): string
    {
        $text = (string) $text;

        return $text === strip_tags($text) ? nl2br(e($text)) : $text;
    }
}
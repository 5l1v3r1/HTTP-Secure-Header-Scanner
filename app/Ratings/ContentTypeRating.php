<?php

namespace App\Ratings;

class ContentTypeRating extends Rating
{
    protected function rate()
    {
        $header = $this->getHeader('content-type');

        if ($header === null) {
            $this->rating = 'C';
            $this->comment  = __('The header is not set.');
        } elseif (count($header) > 1) {
            $this->rating = 'C';
            $this->comment  = __('The header is set multiple times.');
        } else {
            $this->rating = 'C';
            $this->comment = __('The header is set without the charset.');

            $header = $header[0];

            if (stripos($header, 'charset=') !== false) {
                $this->rating = 'B';
                $this->comment = __('The header is set with the charset.');
            }

            if (stripos($header, 'charset=utf-8') !== false) {
                $this->rating = 'A';
                $this->comment = __('The header is set with the charset and follows the best practice.');
            }

            // HASEGAWA
            // http://openmya.hacker.jp/hasegawa/public/20071107/s6/h6.html?file=datae.txt
            elseif (stripos($header, 'utf8') !== false) {
                $this->rating = 'C';
                $this->comment = __('The given charset is wrong and thereby ineffective.') . __('The correct writing is: charset=utf-8');
            } elseif (
                (stripos($header, 'Windows-31J') !== false) ||
                (stripos($header, 'CP932') !== false) ||
                (stripos($header, 'MS932') !== false) ||
                (stripos($header, 'MS942C') !== false) ||
                (stripos($header, 'sjis') !== false) ||
                (stripos($header, 'jis') !== false)
            ) {
                $this->rating = 'C';
                $this->comment = __('The given charset is wrong and thereby ineffective.') . __('Best practice is: charset=utf-8');
            }
        }
    }

    public static function getDescription()
    {
        // W3C
        // https://www.w3.org/International/articles/http-charset/index.en
        // TODO: Translate
        return __('When a server sends a document to a user agent (eg. a browser) it also sends information in the Content-Type field of the accompanying HTTP header about what type of data format this is. This information is expressed using a MIME type label. Documents transmitted with HTTP that are of type text, such as text/html, text/plain, etc., can send a charset parameter in the HTTP header to specify the character encoding of the document.');
    }

    public static function getBestPractice()
    {
        // W3C
        // https://www.w3.org/International/articles/http-charset/index.en
        return 'text/html; charset=utf-8';
    }
}

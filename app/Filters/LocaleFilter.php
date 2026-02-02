<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LocaleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $locale  = $request->getLocale();

        // Check if a language change is requested via GET
        $newLang = $request->getGet('lang');
        if ($newLang && in_array($newLang, config('App')->supportedLocales)) {
            $locale = $newLang;
            $session->set('lang', $locale);
        } elseif ($session->has('lang')) {
            $locale = $session->get('lang');
        }

        service('language')->setLocale($locale);
        $request->setLocale($locale);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}

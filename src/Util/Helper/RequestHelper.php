<?php

namespace App\Util\Helper;

use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{
    public static function retrieveParams(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $request->getContent();
        }

        return $request->request->all();
    }
}

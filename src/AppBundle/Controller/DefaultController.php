<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Post("/test")
     */
    public function indexAction(Request $request) {
        return $request->request->all();
    }
}

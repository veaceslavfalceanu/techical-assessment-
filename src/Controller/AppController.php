<?php

namespace App\Controller;

use App\Entity\Matrix;
use App\ValueObject\Point;
use App\Service\NodeValidator;
use App\Service\SessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/initialize")
     */
    public function initialize()
    {
        $this->sessionManager->clearSession();
        $this->sessionManager->sessionStart();

        $this->sessionManager->setMatrix();

        return new JsonResponse([
            'msg' => "INITIALIZE",
            'body' => [
                "newLine" => null,
                "heading" => "Player 1",
                "message" => "Awaiting Player 1's Move"
            ]
        ]);
    }

    /**
     * @Route("/node-clicked")
     */
    public function nodeClicked(Request $request, NodeValidator $nodeValidator, Matrix $matrix)
    {
        $requestPoint = json_decode($request->getContent(), true);
        $point = new Point($requestPoint['x'], $requestPoint['y']);

        $this->sessionManager->sessionStart();

        return new JsonResponse($nodeValidator->processCurrentNode($point));
    }

    /**
     * @Route("/error")
     */
    public function error()
    {
        return new JsonResponse([
            "error" => "Invalid type for `id`: Expected INT but got a STRING"
        ]);
    }
}
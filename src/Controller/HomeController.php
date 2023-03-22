<?php

/*
 * HomeController (This file is Controller and responsible for get http request amd send back to response)
 * Author : Amar Shah
 * Company : QuanticEdge Software Solutions
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Service\FruityService;

class HomeController extends AbstractController
{
    /**
     * (This method include code of main page (get data from DB and listing on Home Page))
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(FruityService $fruityService, Request $request): Response
    {
        $pagination = $fruityService->get($request);
        $getGlobalSumFacts = $fruityService->getGlobalSumFacts("all");
        return $this->render('home/index.html.twig', ['pagination' => $pagination, 'getGlobalSumFacts' =>  $getGlobalSumFacts]);
    }

    /**
     * (This method include code of favorite fruits list page (get data from DB and listing on favorite fruits list Page))
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     * @Route("/favorite", name="favorite", methods={"GET","POST"})
     */
    public function favorite(FruityService $fruityService, Request $request): Response
    {
        $pagination = $fruityService->get($request, $fav_page = true);
        $getGlobalSumFacts = $fruityService->getGlobalSumFacts("favorite");
        return $this->render('home/favorite.html.twig', ['pagination' => $pagination, 'getGlobalSumFacts' => $getGlobalSumFacts]);
    }

    /**
     * (This method include code of action perform (add fruit to favorite fruits list,remove from favorite fruits list,delete))
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     * @Route("/home/makeAction", name="makeAction", methods={"GET","POST"})
     */
    public function makeAction(Request $request, FruityService $fruityService): Response
    {
        $data = $fruityService->makeAction($request);
        return new Response($data);
    }
}

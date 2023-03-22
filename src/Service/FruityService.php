<?php

/*
 * FruityService (This file is Service file and responsible for getting request from controller and response get from repository and send response back to controller  )
 * Author : Amar Shah
 * Company : QuanticEdge Software Solutions
 */

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Entity\Fruity;


class FruityService
{
    protected $doctrine;
    protected $paginator;
    protected $serializer;
    protected $security;

    public function __construct(Security $security,  ManagerRegistry $doctrine, PaginatorInterface $paginator, SerializerInterface $serializer)
    {
        $this->doctrine = $doctrine;
        $this->security = $security;
        $this->paginator = $paginator;
        $this->serializer = $serializer;
    }

    /**
     * (This method include code of fetch data from repository based on controller request get (called by controller))
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function get($request, $fav_page = false)
    {
        $filters = [
            'name_filter' => $request->request->get('name_filter'),
            'family_filter' => $request->request->get('family_filter'),
        ];
        $qb = $this->doctrine->getRepository(Fruity::class)->getSearchQuery($filters, $fav_page);

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );
        return $pagination;
    }

    /**
     * (This method include code of fetch data from repository based on controller request getGlobalSumFacts (called by controller))
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function getGlobalSumFacts($type)
    {
        return $this->doctrine->getRepository(Fruity::class)->getGlobalSum($type);
    }

    /**
     * (This method include code of fetch data from repository based on controller request makeAction (called by controller))
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function makeAction($request)
    {
        if ($request->request->get('action') == 'delete') {
            return $this->doctrine->getRepository(Fruity::class)->delete($request->request->get('id'));
        } elseif ($request->request->get('action') == 'add_to_fav') {
            return  $this->doctrine->getRepository(Fruity::class)->addToFav($request->request->get('id'));
        } elseif ($request->request->get('action') == 'remove_from_fav') {
            return $this->doctrine->getRepository(Fruity::class)->removeFromFav($request->request->get('id'));
        }
    }
}

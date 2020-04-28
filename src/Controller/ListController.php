<?php

namespace App\Controller;

use App\Entity\TaskList;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\TaskListRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;

class ListController extends AbstractFOSRestController
{
    private $repository;
    private $entityManager;

    public function __construct(TaskListRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getListsAction()
    {
        $data = $this->repository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }

    public function getListAction($id){
        $data = $this->repository->findBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\RequestParam(name="title", description="List title", nullable=false)
     */
    public function postListsAction(ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get('title');
        if($title) {
            $list = new TaskList();
            $list->setTitle($title);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view(Response::HTTP_CREATED);
        } else {
            return view(Response::HTTP_BAD_REQUEST);
        }
    }
}

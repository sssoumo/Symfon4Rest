<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

class ListController extends AbstractFOSRestController
{
    /**
     * @var TaskListRepository
     */
    private $taskListRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManager)
    {

        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
    }


    public function getListsAction()
    {
        $data = $this->taskListRepository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }

    public function getListAction(int $id)
    {
        $data = $this->taskListRepository->findOneBy(['id'=>$id]);
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     */
    public function postListsAction(ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get('title');
        if($title){
            $list= new TaskList();
            $list->setTitle($title);
            $this->entityManager->persist($list);
            $this->entityManager->flush();

            return $this->view($list, Response::HTTP_CREATED);
        }
        return $this->view(['title'=>'This cannot be null'], Response::HTTP_OK);
    }
}

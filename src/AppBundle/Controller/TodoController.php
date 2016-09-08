<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Utils\JsonUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;



/**
 * @Route("/todo")
 */
class TodoController extends Controller
{
    /**
     * @Route("/", name="apiTodoList")
     * @Route("", name="apiTodoList2")
     * @Method({"GET"})
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function listAction()
    {
        $todoList = $this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();
        $serializer = $this->get('serializer');
        $response = new Response($serializer->serialize($todoList, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/", name="apiTodoAdd")
     * @Route("", name="apiTodoAdd2")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function addTodoItem(Request $request)
    {
        $response = new Response('no-data');
        if (JsonUtils::isJSONRequest($request)) {
            $todo = JsonUtils::deserializeFromJson($request->getContent(), 'AppBundle\\Entity\\Todo');
            $todoRepository = $this->getDoctrine()->getRepository('AppBundle:Todo');
            $errors = $todoRepository->validate( $this->get('validator'), $todo );
            if(count($errors) > 0 && count($errors[0]) > 0 ) {
                $responseObject = array(
                    (string) $errors[0][0]
                );
            } else {
                $todoRepository->persistAndFlushAll($todo);
                $responseObject = array(
                    'message' => 'Data saved correctly!'
                );
            }

            $response = new Response(json_encode($responseObject));
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }

    /**
     * @Route("/{id}", name="apiTodoEdit")
     * @Method({"PUT"})
     *
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function editTodoItem(Request $request, $id)
    {

        if (JsonUtils::isJSONRequest($request) && !is_array($request->getContent())) {
            /** @var Todo $newTodo */
            $newTodo = JsonUtils::deserializeFromJson($request->getContent(), 'AppBundle\\Entity\\Todo');
            $todoRepository = $this->getDoctrine()->getRepository('AppBundle:Todo');
            $errors = $todoRepository->validate( $this->get('validator'), $newTodo );
            if(count($errors) > 0 && count($errors[0]) > 0  ) {
                $responseObject = array(
                    (string) $errors[0][0]
                );
            } else {

                $responseObject = array(
                    'message' => $todoRepository->update($id, $newTodo)
                );
            }

        } else {
            $responseObject = array(
                'message' => 'Bad JSON structure!'
            );
        }

        $serializer = $this->get('serializer');
        $response = new Response($serializer->serialize($responseObject, 'json'));
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }

    /**
     * @Route("/{id}", name="apiDeleteTodo")
     * @Method({"DELETE"})
     * @throws \LogicException
     */
    public function deleteTodoItem($id)
    {
        $message = $this->getDoctrine()->getRepository('AppBundle:Todo')->deleteAndFlushOne($id);
        $responseObject = array( 'message' => $message );
        $serializer = $this->get('serializer');
        $response = new Response($serializer->serialize($responseObject, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{id}", name="apiCompleteTodo")
     * @Method({"PATCH"})
     * @throws \LogicException
     */
    public function completeTodoItem($id)
    {
        $message = $this->getDoctrine()->getRepository('AppBundle:Todo')->complete($id);
        $responseObject = array(
            'message' => $message
        );
        $serializer = $this->get('serializer');
        $response = new Response($serializer->serialize($responseObject, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}

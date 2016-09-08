<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Utils\JsonUtils;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *  resource=true,
     *  description="Todo list action. This request path return JSON list of todo items saved in the database",
     *  views = { "default", "premium" },
     *  statusCodes={
     *     200="Returned when successful",
     *  }
     * )
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
     * @ApiDoc(
     *  resource=true,
     *  description="Add new Todo item. This request path can add new Todo item or more than one TODO item",
     *  views = { "default", "premium" },
     *  input="AppBundle\Entity\Todo",
     *  statusCodes={
     *      200="Returned when successful",
     *  },
     *  requirements={
     *     {
     *          "name"="_format",
     *          "dataType"="json",
     *          "description"="JSON entity to add. Returns JSON message after validation or save new object to database",
     *          "requirement"="json",
     *          "required"="true"
     *      }
     *  }
     * )
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
     * @ApiDoc(
     *  resource=true,
     *  description="Edit Todo item",
     *  views = { "default", "premium" },
     *  input="AppBundle\Entity\Todo",
     *  statusCodes={
     *      200="Returned when successful",
     *  },
     *  requirements={
     *     {
     *          "name"="_format",
     *          "dataType"="json",
     *          "description"="JSON entity to add. Returns JSON message after validation or save new object to database",
     *          "requirement"="json",
     *          "required"="true"
     *      }
     *  }
     * )
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
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete Todo item",
     *  views = { "default", "premium" },
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )
     *
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
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Complete Todo item",
     *  views = { "default", "premium" },
     *  statusCodes={
     *      200="Returned when successful",
     *  },
     * )
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

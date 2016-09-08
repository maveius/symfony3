<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TodoRepository")
 * @ORM\Table(name="todo")
 */
class Todo
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * Encapsulation (private field)
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=false, length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Type("string")
     */
    private $description;

    /**
     * @ORM\Column(name="deadline", type="datetime", nullable=false)
     * @var DateTime
     * @Assert\NotBlank()
     * @Assert\GreaterThan("today")
     * @Assert\Type("DateTime|string")
     */
    private $deadline;

    /**
     * @ORM\Column(name="completed", type="boolean", nullable=false, columnDefinition="TINYINT(1) default FALSE")
     * @Assert\NotBlank()
     * @Assert\Type("boolean")
     */
    private $completed;

    /**
     * @return integer
     * Encapsulation (public getter)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * Encapsulation (public setter)
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param mixed $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = is_string($deadline) ? DateTime::createFromFormat('Y-m-d G:i:s', $deadline ) : $deadline;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = filter_var($completed, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param Todo $newTodo
     */
    public function update($newTodo){
        $this->setName($newTodo->getName());
        $this->setDescription($newTodo->getDescription());
        $this->setDeadline($newTodo->getDeadline());
        $this->setCompleted($newTodo->getCompleted());
    }


}
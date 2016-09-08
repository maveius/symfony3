<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TodoRepository extends EntityRepository
{

    /**
     * @param ValidatorInterface $validator
     * @param $todo
     * @return array
     */
    public function validate($validator, $todo) {

        $errorsArray = array();
        if( is_array($todo) ) {
            foreach ($todo as $item) {
                $item->setDeadline($item->getDeadline());
                /** @noinspection DisconnectedForeachInstructionInspection */
                $errorsArray[] = $validator->validate($todo);
            }
        } else {

            /** @var Todo $todo */
            $todo->setDeadline($todo->getDeadline());
            $errorsArray[] = $validator->validate($todo);
        }

        return $errorsArray;

    }

    public function persistAndFlushAll($todo) {

        if( is_array($todo) ) {
            foreach ($todo as $item) {
                /** @var Todo $item */
                $this->persistAndFlushOne($item);
            }
        } else {

            /** @var Todo $todo */
            $this->persistAndFlushOne($todo);
        }


    }

    /**
     * @param $todo
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function persistAndFlushOne($todo)
    {
        $this->_em->persist($todo);
        $this->_em->flush();
    }

    public function update($id, $newTodo){
        /** @var Todo $todo */
        $todo = $this->findOneBy(['id' => $id]);
        if($todo !== null) {
            $todo->update($newTodo);
            $this->persistAndFlushAll($todo);
            return 'Data modified correctly!';
        } else {
            return 'Object not found!';
        }

    }

    public function deleteAndFlushOne($id) {

        $todo = $this->findOneBy(['id' => $id]);

        if($todo !== null) {
            $this->_em->remove($todo);
            $this->_em->flush();
            return 'Data deleted correctly!';
        } else {
            return 'Object not found!';
        }
    }

    public function complete($id)
    {
        /** @var Todo $todo */
        $todo = $this->findOneBy(['id' => $id]);
        if($todo !== null) {
            $todo->setCompleted(true);
            $this->persistAndFlushAll($todo);
            return 'Data completed correctly!';
        } else {
            return 'Object not found!';
        }

    }
}

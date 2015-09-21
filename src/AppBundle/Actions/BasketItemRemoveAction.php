<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Basket;
use Codifico\Component\Actions\Action\RemoveAction;
use AppBundle\Event\EntityRemoveEvent;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemRemoveAction extends RemoveAction
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ActionRepositoryInterface $repository,
        UserInterface $user
    )
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($repository);
        $this->user = $user;
    }

    /**
     * @param $object
     * @return void
     */
    public function postRemove($object)
    {
        $this->dispatcher->dispatch('action.remove', new EntityRemoveEvent($object));
    }

    /**
     * Creates new entity
     *
     * @return mixed
     */
    public function execute()
    {
        $this->object->inactivate();

        $this->postRemove($this->object);
    }

    /**
     * @param Basket $basket
     * @return BasketItemUpdateAction
     */
    public function setBasketItem(Basket $basket)
    {
        if (!$basket->isOwnedBy($this->user)) {
            throw new NotFoundHttpException("Basket item does not exists");
        }
        parent::setObject($basket);

        return $this;
    }
}

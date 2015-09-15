<?php

namespace AppBundle\Controller;

use AppBundle\Actions\OrderActivateAction;
use AppBundle\Actions\OrderCreateAction;
use AppBundle\Actions\OrderIndexAction;
use AppBundle\Actions\OrderRemoveAction;
use AppBundle\Actions\OrderUpdateAction;
use AppBundle\Entity\Order;
use AppBundle\Request\Criteria;
use FOS\RestBundle\Util\Codes;

class OrdersController
{
    use RestTrait;

    /**
     * @var OrderCreateAction
     */
    private $create;

    /**
     * @var OrderUpdateAction
     */
    private $update;

    /**
     * @var OrderIndexAction
     */
    private $index;

    /**
     * @var OrderRemoveAction
     */
    private $remove;

    /**
     * @var OrderActivateAction
     */
    private $activate;

    public function __construct(OrderCreateAction $create, OrderUpdateAction $update, OrderIndexAction $index, OrderRemoveAction $remove, OrderActivateAction $activate)
    {
        $this->create = $create;
        $this->update = $update;
        $this->index = $index;
        $this->remove = $remove;
        $this->activate = $activate;
    }

    /**
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        return $this->renderRestView(
            $this->index->setCriteria($criteria)->execute(),
            Codes::HTTP_OK,
            [],
            ['orders_index']
        );
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function createAction()
    {
        return $this->renderRestView(
            $this->create->execute(),
            Codes::HTTP_CREATED,
            [],
            ['orders_create']
        );
    }

    /**
     *
     * @param Order $order
     */
    public function editAction(Order $order)
    {
        $this->renderRestView(
            $this->update->setOrder($order)->execute(),
            Codes::HTTP_OK,
            [],
            ['orders_update']
        );
    }

    /**
     * @param Order $order
     */
    public function removeAction(Order $order)
    {
        $this->remove->setOrder($order)->execute();
    }

    /**
     * @param Order $order
     *
     * @return \FOS\RestBundle\View\View
     */
    public function viewAction(Order $order)
    {
        return $this->renderRestView($order, Codes::HTTP_OK, [], ['orders_view']);
    }

    /**
     * @param Order $order
     * @return \FOS\RestBundle\View\View
     */
    public function activateAction(Order $order)
    {
        $this->activate->setOrder($order)->execute();
    }
}

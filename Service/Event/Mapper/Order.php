<?php

namespace Scue\ESputnik\Service\Event\Mapper;

use InvalidArgumentException;
use Magento\Catalog\Model\Product;
use Magento\Sales\Model\Order as OrderModel;
use Magento\Sales\Model\Order\Item as OrderItem;
use Scue\ESputnik\Api\Http\MapperInterface;

class Order implements MapperInterface
{
    public function handle(array $request): array
    {
        if (! isset($request['entity']) || ! isset($request['status'])) {
            throw new InvalidArgumentException('Order or order status wasn\'t provided');
        }

        /**
         * @var OrderModel $order
         */
        $order = $request['entity'];

        return [
            'orders' => [
                $this->collectOrderData($request['status'], $order)
            ]
        ];
    }

    protected function collectOrderData(string $status, $order)
    {
        $items = [];

        foreach ($order->getAllVisibleItems() as $item) {
            /**
             * @var OrderItem $item
             */
            $items[] = [
                'name' => $item->getName(),
                'cost' => round($item->getPrice(), 2),
                'category' => $this->getCategoryName($item->getProduct()),
                'quantity' => (int)$item->getQtyOrdered(),
                'externalItemId' => (string)$item->getSku()
            ];
        }

        return [
            'status' => $status,
            'date' => $order->getCreatedAtFormatted('Y-m-d\TH:m:s'),
            'externalOrderId' => $order->getIncrementId(),
            'externalCustomerId' => $order->getCustomerId(),
            'totalCost' => round($order->getSubtotal(), 2),
            'items' => $items
        ];
    }

    /**
     * @param Product $product
     * @return string
     */
    protected function getCategoryName($product): string
    {
        $categories = $product->getCategoryCollection()->addAttributeToSelect('name');

        return $categories->getLastItem()->getName();
    }
}
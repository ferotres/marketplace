<?php


namespace App\Shared\Domain\Model;


interface Event
{
    /**
     * @Event("App\Catalog\Domain\Model\ProductWasCreated")
     */
    const PRODUCT_WAS_CREATED = 'product_was_created';

    /**
     * @Event("App\Catalog\Domain\Model\ProductWasDeleted")
     */
    const PRODUCT_WAS_DELETED = 'product_was_deleted';

    /**
     * @Event("App\Catalog\Domain\Model\ProductWasDeleted")
     */
    const PRODUCT_WAS_UPDATED = 'product_was_updated';

    /**
     * @Event("App\Customer\Domain\Model\CartWasCommited")
     */
    const CART_WAS_COMMITTED = 'cart_was_committed';

    /**
     * @Event("App\Customer\Domain\Model\CartWasCreated")
     */
    const CART_WAS_CREATED = 'cart_was_created';

    /**
     * @Event("App\Customer\Domain\Model\CartWasRemoved")
     */
    const CART_WAS_REMOVED = 'cart_was_removed';


    public function id(): string;
}
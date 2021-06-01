<?php
declare(strict_types=1);

namespace IntegerNet\AddToCartGraphQl\Plugin;

use Magento\Checkout\Model\Session;
use Magento\Quote\Model\QuoteManagement;

/**
 * Force setting cart id for guests - this is required for graphql add to cart to work
 */
class ForceGuestCart
{
    private QuoteManagement $quoteManagement;

    public function __construct(
        QuoteManagement $quoteManagement)
    {
        $this->quoteManagement = $quoteManagement;
    }

    public function beforeGetQuote(Session $subject)
    {
        if (!($subject->getQuoteId())) {
            $quoteId = $this->quoteManagement->createEmptyCart();
            $subject->setQuoteId($quoteId);
        }
    }
}

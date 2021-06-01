<?php
declare(strict_types=1);

namespace IntegerNet\AddToCartGraphQl\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Hyva\Theme\ViewModel\CurrentProduct;

class AddToCartGraphQl implements ArgumentInterface
{
    private CurrentProduct $currentProduct;

    public function __construct(
        CurrentProduct $currentProduct)
    {
        $this->currentProduct = $currentProduct;
    }

    private function getProduct() : ProductInterface
    {
        return $this->currentProduct->get();
    }

    public function isProductConfigurable(): bool
    {
        return $this->getProduct()->getTypeId() === Configurable::TYPE_CODE;
    }

    public function getAddToCartQuery(): string
    {
        if ($this->isProductConfigurable()) {
            return $this->getAddToCartQueryProductTypeConfigurable();
        }

        return $this->getAddToCartQueryProductTypeSimple();
    }

    private function getAddToCartQueryProductTypeSimple(): string
    {
        return '{
        addProductsToCart(
            cartId: "%cartId"
            cartItems: [
              {
                quantity: %qty
                sku: "%sku"
              }
            ]
        ) {
        cart {
          items {
            product {
              name
              sku
            }
            quantity
          }
        }
      }
    }';
    }

    private function getAddToCartQueryProductTypeConfigurable(): string
    {
        return '{
            addProductsToCart(
                cartId: "%cartId"
                cartItems: [
                  {
                    quantity: %qty
                    sku: "%sku"
                    selected_options: [%selectedOptions]
                  }
                ]
            ) {
            cart {
              items {
                product {
                  name
                  sku
                }
                quantity
              }
            }
          }
        }';
    }
}

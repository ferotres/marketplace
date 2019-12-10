<?php


namespace App\Customer\Infrastructure\UI\Http\Controller;

use App\Customer\Application\Service\AddProductToCart;
use App\Customer\Application\Service\CommitCart;
use App\Customer\Application\Service\CreateCart;
use App\Customer\Application\Service\RemoveCart;
use App\Customer\Application\Service\RemoveProductOfCart;
use App\Customer\Domain\Model\Exception\CartIsCommited;
use App\Customer\Domain\Model\Exception\CartIsEmpty;
use App\Customer\Domain\Model\Exception\CartNotExist;
use App\Customer\Domain\Model\Exception\CustomerNotExist;
use App\Customer\Domain\Model\Exception\ProductNotAvailable;
use App\Customer\Infrastructure\UI\DataTransformer\CartDataTransformer;
use App\Shared\Infrastructure\UI\Http\Controller\ApiController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartController extends ApiController
{
    /** @var CreateCart */
    private $createCart;
    /** @var RemoveCart */
    private $removeCart;
    /** @var AddProductToCart */
    private $addProductToCart;
    /** @var RemoveProductOfCart */
    private $removeProductOfCart;
    /**  @var CommitCart */
    private $commitCart;

    public function __construct(
        CreateCart $createCart,
        RemoveCart $removeCart,
        AddProductToCart $addProductToCart,
        CommitCart $commitCart,
        RemoveProductOfCart $removeProductOfCart
    ) {
        $this->createCart = $createCart;
        $this->removeCart = $removeCart;
        $this->addProductToCart = $addProductToCart;
        $this->removeProductOfCart = $removeProductOfCart;
        $this->commitCart = $commitCart;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);
            Assertion::keyExists($content, 'customerId');
            Assertion::notEmpty($content['customerId']);
            Assertion::uuid($content['customerId']);

            $cart = $this->createCart->execute($content['customerId']);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (CustomerNotExist $exception) {
            return $this->exceptionResponse("Customer not exist", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(CartDataTransformer::write($cart)->read(), 200);
    }

    public function remove(Request $request, string $cartId): JsonResponse
    {
        try {

            Assertion::uuid($cartId);

            $this->removeCart->execute($cartId);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (CartNotExist $exception) {
            return $this->exceptionResponse("Cart not exist", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(["Cart deleted!"], 200);
    }

    public function addProduct(Request $request, string $cartId, string $productId): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::uuid($cartId);
            Assertion::uuid($productId);
            Assertion::keyExists($content, 'quantity');
            Assertion::notEmpty($content['quantity']);
            Assertion::integer($content['quantity']);

            $cart = $this->addProductToCart->execute($cartId, $productId, $content['quantity']);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (CustomerNotExist $exception) {
            return $this->exceptionResponse("Customer not exist", 404);
        } catch (CartIsCommited $exception) {
            return $this->exceptionResponse("Cart is Already committed", 400);
        } catch (ProductNotAvailable $exception) {
            return $this->exceptionResponse("Product not Available", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(CartDataTransformer::write($cart)->read(), 200);
    }

    public function removeProduct(Request $request, string $cartId, string $cartItemId): JsonResponse
    {
        try {

            Assertion::uuid($cartId);
            Assertion::uuid($cartItemId);

            $cart = $this->removeProductOfCart->execute($cartId, $cartItemId);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (CartNotExist $exception) {
            return $this->exceptionResponse("Cart not exist", 404);
        } catch (CartIsCommited $exception) {
            return $this->exceptionResponse("Cart is Already committed", 400);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(CartDataTransformer::write($cart)->read(), 200);
    }

    public function commit(string $cartId): JsonResponse
    {
        try {

            Assertion::uuid($cartId);

            $cart = $this->commitCart->execute($cartId);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (CartNotExist $exception) {
            return $this->exceptionResponse("Cart not exist", 404);
        } catch (CartIsEmpty $exception) {
            return $this->exceptionResponse("Cart is empty", 400);
        } catch (ProductNotAvailable $exception) {
            return $this->exceptionResponse($exception->getMessage(), 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(CartDataTransformer::write($cart)->read(), 200);
    }
}
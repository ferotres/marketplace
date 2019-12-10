<?php


namespace App\Seller\Infrastructure\UI\Http\Controller;

use App\Catalog\Domain\Model\Exception\ProductNotExist;
use App\Seller\Application\Service\AddProductToSeller;
use App\Seller\Application\Service\CreateSeller;
use App\Seller\Application\Service\DeleteSeller;
use App\Seller\Application\Service\RemoveProductOfSeller;
use App\Seller\Application\Service\UpdateProductOfSeller;
use App\Seller\Application\Service\UpdateSeller;
use App\Seller\Domain\Model\Exception\SellerAreadyExist;
use App\Seller\Domain\Model\Exception\SellerNotExist;
use App\Seller\Domain\Model\Exception\SellerProductAlreadyExist;
use App\Seller\Domain\Model\Exception\SellerProductNotExist;
use App\Seller\Infrastructure\UI\DataTransformer\SellerDataTransformer;
use App\Seller\Infrastructure\UI\DataTransformer\SellerProductDataTransfomer;
use App\Shared\Infrastructure\UI\Http\Controller\ApiController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class SellerController extends ApiController
{
    /** @var CreateSeller */
    private $createSeller;
    /** @var DeleteSeller */
    private $deleteSeller;
    /** @var UpdateSeller */
    private $updateSeller;
    /** @var AddProductToSeller */
    private $addProductToSeller;
    /**  @var RemoveProductOfSeller */
    private $removeProductOfSeller;
    /**  @var UpdateProductOfSeller */
    private $updateProductOfSeller;

    public function __construct(
        CreateSeller $createSeller,
        DeleteSeller $deleteSeller,
        UpdateSeller $updateSeller,
        AddProductToSeller $addProductToSeller,
        RemoveProductOfSeller $removeProductOfSeller,
        UpdateProductOfSeller $updateProductOfSeller
    ) {
        $this->createSeller = $createSeller;
        $this->deleteSeller = $deleteSeller;
        $this->updateSeller = $updateSeller;
        $this->addProductToSeller = $addProductToSeller;
        $this->removeProductOfSeller = $removeProductOfSeller;
        $this->updateProductOfSeller = $updateProductOfSeller;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::keyExists($content, 'name');
            Assertion::keyExists($content, 'email');
            Assertion::notEmpty($content['name']);
            Assertion::string($content['name']);
            Assertion::notEmpty($content['email']);
            Assertion::email($content['email']);

            $product = $this->createSeller->execute($content['name'], $content['email']);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (SellerAreadyExist $exception) {
            return $this->exceptionResponse("Seller Already Exist", 400);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(SellerDataTransformer::write($product)->read());
    }


    public function update(Request $request, string $sellerId): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::keyExists($content, 'name');
            Assertion::keyExists($content, 'email');
            Assertion::uuid($sellerId);
            Assertion::notEmpty($content['name']);
            Assertion::string($content['name']);
            Assertion::notEmpty($content['email']);
            Assertion::email($content['email']);

            $product = $this->updateSeller->execute($sellerId, $content['name'], $content['email']);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (SellerNotExist $exception) {
            return $this->exceptionResponse("Seller not found", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(SellerDataTransformer::write($product)->read());
    }

    public function delete(string $sellerId): JsonResponse
    {
        try {

            Assertion::uuid($sellerId);
            $this->deleteSeller->execute($sellerId);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (SellerNotExist $exception) {
            return $this->exceptionResponse("Seller not found", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(["Seller Deleted"]);
    }

    public function addProduct(Request $request, string $sellerId, string $productId): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::keyExists($content, 'stock');
            Assertion::keyExists($content, 'amount');
            Assertion::uuid($sellerId);
            Assertion::uuid($productId);
            Assertion::notEmpty($content['stock']);
            Assertion::integer($content['stock']);
            Assertion::notEmpty($content['amount']);
            Assertion::float($content['amount']);

            $sellerProduct = $this->addProductToSeller->execute(
                $sellerId,
                $productId,
                $content['stock'],
                $content['amount']
            );

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (SellerProductAlreadyExist $exception) {
            return $this->exceptionResponse("Seller Already has a product", 400);
        } catch (SellerNotExist $exception) {
            return $this->exceptionResponse("Seller not found", 404);
        } catch (ProductNotExist $exception) {
            return $this->exceptionResponse("Product not found", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(SellerProductDataTransfomer::write($sellerProduct)->read());
    }

    public function removeProduct(Request $request, string $sellerProductId): JsonResponse
    {
        try {

            Assertion::uuid($sellerProductId);
            $this->removeProductOfSeller->execute($sellerProductId);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (SellerProductNotExist $exception) {
            return $this->exceptionResponse("Seller Product not exist", 400);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(["Product removed for a seller"]);
    }

    public function updateProduct(Request $request, string $sellerProductId): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::keyExists($content, 'stock');
            Assertion::keyExists($content, 'amount');
            Assertion::uuid($sellerProductId);
            Assertion::notEmpty($content['stock']);
            Assertion::integer($content['stock']);
            Assertion::notEmpty($content['amount']);
            Assertion::float($content['amount']);

            $sellerProduct = $this->updateProductOfSeller->execute(
                $sellerProductId,
                $content['stock'],
                $content['amount']
            );

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (SellerProductNotExist $exception) {
            return $this->exceptionResponse("Seller Product not exist", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(SellerProductDataTransfomer::write($sellerProduct)->read());
    }
}
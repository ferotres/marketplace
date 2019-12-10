<?php


namespace App\Catalog\Infrastructure\UI\Http\Controller;

use App\Catalog\Application\Service\CreateProduct;
use App\Catalog\Application\Service\DeleteProduct;
use App\Catalog\Application\Service\RetrieveProductCatalog;
use App\Catalog\Application\Service\UpdateProduct;
use App\Catalog\Domain\Model\Exception\ProductNotExist;
use App\Catalog\Infrastructure\UI\DataTransformer\CatalogDataTransformer;
use App\Catalog\Infrastructure\UI\DataTransformer\ProductDataTransformer;
use App\Shared\Infrastructure\UI\Http\Controller\ApiController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends ApiController
{

    /** @var RetrieveProductCatalog */
    private $retrieveProductCatalog;
    /** @var CreateProduct */
    private $createProduct;
    /** @var DeleteProduct */
    private $deleteProduct;
    /** @var UpdateProduct */
    private $updateProduct;

    public function __construct(
        RetrieveProductCatalog $retrieveProductCatalog,
        CreateProduct $createProduct,
        DeleteProduct $deleteProduct,
        UpdateProduct $updateProduct
    ) {
        $this->retrieveProductCatalog = $retrieveProductCatalog;
        $this->createProduct = $createProduct;
        $this->deleteProduct = $deleteProduct;
        $this->updateProduct = $updateProduct;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $products = $this->retrieveProductCatalog->execute($request->get('offset'));
        } catch (\Throwable $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->response(CatalogDataTransformer::write($products)->read());
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::keyExists($content, 'name');
            Assertion::keyExists($content, 'reference');
            Assertion::notEmpty($content['name']);
            Assertion::string($content['name']);
            Assertion::notEmpty($content['reference']);
            Assertion::string($content['reference']);

            $product = $this->createProduct->execute($content['name'], $content['reference']);

        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(ProductDataTransformer::write($product)->read());
    }

    public function delete(string $productId): JsonResponse
    {
        try {
            Assertion::uuid($productId);
            $this->deleteProduct->execute($productId);
        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (ProductNotExist $exception) {
            return $this->exceptionResponse("Product Not Exist in catalog", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(["Product Deleted"]);
    }

    public function update(Request $request, string $productId): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            Assertion::keyExists($content, 'name');
            Assertion::keyExists($content, 'reference');
            Assertion::uuid($productId);
            Assertion::notEmpty($content['name']);
            Assertion::string($content['name']);
            Assertion::notEmpty($content['reference']);
            Assertion::string($content['reference']);

            $product = $this->updateProduct->execute($productId, $content['name'], $content['reference']);
        } catch (AssertionFailedException $exception) {
            return $this->exceptionResponse($exception->getMessage(), 400);
        } catch (ProductNotExist $exception) {
            return $this->exceptionResponse("Product Not Exist in catalog", 404);
        } catch (\Throwable $exception) {
            return $this->exceptionResponse("Internal Server Error");
        }

        return $this->response(ProductDataTransformer::write($product)->read());
    }
}
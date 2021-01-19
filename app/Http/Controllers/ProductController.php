<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Rules\CategoryAmountRule;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validation;
use Illuminate\Contracts\Validation\Validator;

class ProductController extends Controller
{
    /** @var ProductService */
    protected $productService;

    /**
     * ProductController constructor.
     * @param $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function get(Request $request)
    {
        Validation::make($request->all(), [
            'from_price' => 'integer',
            'to_price' => 'integer',
            'title' => 'string',
            'public' => 'boolean',
            'category_name' => 'string'
        ]);
        $products = $this->productService->filter($request->all());

        return new JsonResponse($products);
    }

    public function create(Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return new ErrorResource($validator);
        }

        $product = $this->productService->create([
            'title' => $request->get('title'),
            'price' => $request->get('price'),
            'public' => $request->get('public')
        ]);
        $product->categories()->sync($request->get('category_ids'));

        return new ProductResource($product);
    }

    public function update(Product $product, Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return new ErrorResource($validator);
        }
        $product->update([
            'title' => $request->get('title'),
            'price' => $request->get('price'),
            'public' => $request->get('public')
        ]);
        $product->categories()->sync($request->get('category_ids'));

        return new ProductResource($product);
    }

    public function delete(Product $product): JsonResponse
    {
        $product->delete();
        return new JsonResponse([
            'message' => 'Product was deleted'
        ]);
    }
    private function validateRequest(Request $request): Validator
    {
        return Validation::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'price' => 'required|integer',
            'public' => 'required|boolean',
            'category_ids' => [
                'required',
                'array',
                'exists:categories,id',
                new CategoryAmountRule()
            ]
        ]);
    }
}

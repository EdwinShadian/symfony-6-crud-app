<?php

namespace App\Controller;

use App\Helper\ApiResponseHelper;
use App\Service\FileService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'products_index', methods: ['GET'])]
    public function index(ProductService $productService): JsonResponse
    {
        $products = $productService->getProducts();

        $response = [];

        foreach ($products as $product) {
            $response[] = $product->toArray();
        }

        return ApiResponseHelper::success($response);
    }

    #[Route('/products', name: 'products_create', methods: ['POST'])]
    public function create(
        Request $request,
        ProductService $productService,
        FileService $uploadService
    ): JsonResponse {
        $data = $request->request->all();
        $photo = $request->files->get('photo');

        if ($photo instanceof File) {
            $publicDir = $this->getParameter('kernel.project_dir').'/public/';
            $data['photoUrl'] = $uploadService->upload($photo, $publicDir);
        }

        $product = $productService->createProduct($data);

        return ApiResponseHelper::success($product->toArray(), 201);
    }

    #[Route('/products/{id}', name: 'products_show', methods: ['GET'])]
    public function show(ProductService $productService, int $id): JsonResponse
    {
        $product = $productService->getProduct($id);

        return ApiResponseHelper::success($product->toArray());
    }

    #[Route('/products/{id}', name: 'products_update', methods: ['POST'])]
    public function update(
        Request $request,
        ProductService $productService,
        FileService $uploadService,
        int $id
    ): JsonResponse {
        $data = $request->request->all();
        $photo = $request->files->get('photo');

        if ($photo instanceof File) {
            $publicDir = $this->getParameter('kernel.project_dir').'/public/';
            $data['photoUrl'] = $uploadService->upload($photo, $publicDir);
        }

        $product = $productService->updateProduct($id, $data);

        return ApiResponseHelper::success($product->toArray());
    }

    #[Route('/products/{id}', name: 'products_delete', methods: ['DELETE'])]
    public function delete(ProductService $productService, int $id): JsonResponse
    {
        $productService->deleteProduct($id);

        return ApiResponseHelper::success(null, 204);
    }
}

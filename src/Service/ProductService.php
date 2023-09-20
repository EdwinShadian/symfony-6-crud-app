<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\ValidationException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private ObjectManager $entityManager;
    private ObjectRepository $productRepository;

    public function __construct(
        private readonly ValidatorInterface $validator,
        ManagerRegistry $doctrine
    ) {
        $this->entityManager = $doctrine->getManager();
        $this->productRepository = $doctrine->getRepository(Product::class);
    }

    public function getProducts(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * @throws ValidationException
     */
    public function createProduct(array $data): Product
    {
        $product = new Product();
        $product->setName($data['name'] ?? '');
        $product->setDescription($data['description'] ?? null);
        $product->setPrice($data['price'] ?? 0);
        $product->setPhotoUrl($data['photoUrl'] ?? null);

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            throw new ValidationException((string) $errors);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function getProduct(int $id)
    {
        $product = $this->productRepository->find($id);

        if (null === $product) {
            throw new NotFoundHttpException();
        }

        return $product;
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->getProduct($id);

        $product->setName($data['name'] ?? '');
        $product->setDescription($data['description'] ?? null);
        $product->setPrice($data['price'] ?? 0);
        $product->setPhotoUrl($data['photoUrl'] ?? null);

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            throw new ValidationException((string) $errors);
        }

        $this->entityManager->flush();

        return $product;
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->getProduct($id);

        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}

# Тестовое задание для компании "Slotegrator"

Используемые технологии:
- PHP 8.1
- Symfony 6.3
- PHP-FPM
- Nginx
- Docker

## Развертывание в локальной среде

1) Поднять контейнеры с помощью Docker Compose:
```shell
docker compose up -d --build
```
2) Скопировать настройки окружения из .env.local.example в .env.local (он заполнен для локального окружения)
```shell
docker exec -it symfony-6-crud-app cp .env.local.example .env.local
```
3) Установить пакеты Composer:
```shell
docker exec -it symfony-6-crud-app composer install --no-scripts
```
4) Выполнить миграцию в основную БД:
```shell
docker exec -it symfony-6-crud-app php bin/console d:m:m -q
```
5) Готово! Можно попробовать методы API:
    - GET /api/products - получить список товаров
    - POST /api/products - создать товар
      - name: наименование товара
      - price: цена
      - description: описание товара (необязательно)
      - photo: изображение (файлом) (необязательно)
    - GET /api/products/{id} - получить товар по id
    - POST /api/products/{id} - обновить товар (поля аналогичны таковым при создании)
    - DELETE /api/products/{id} - удалить товар из базы
   
   Изображения получаются по ссылке в ответе и хранятся в файловой системе сервера.

## Для запуска тестов:

1) Создать тестовую БД и выполнить миграцию:
```shell
docker exec -it symfony-6-crud-app /bin/bash -c "php bin/console --env=test d:d:c && php bin/console --env=test d:m:m -q"
```
2) Запустить тесты:
```shell
docker exec -it symfony-6-crud-app php vendor/phpunit/phpunit/phpunit --configuration /var/www/html/phpunit.xml.dist /var/www/html/tests
```

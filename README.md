# PHP simplest API Template

## quick start

copy .env.example to .env

```shell
cp .env.example .env
```

install packages

```shell
composer install
composer start # running php server on http://localhost:8000
```

## api response demo

<http://localhost:8000/api/demo>

```json
{
  "code": 200,
  "data": [
    {
      "id": 1,
      "name": "John"
    },
    {
      "id": 2,
      "name": "Allen"
    }
  ],
  "message": "",
  "execution_time": "10.347ms"
}
```

## route register

```php
# ./src/index.php
# callback function
use demo\decorators\Param;
$app->router->get('/path/{id}', fn(#[Param('id')] string $id) => ['id' => $id]);


# class method
use demo\modules\demo\DemoController;
$app->router->post('path', [DemoController::class, 'methodName'])
```

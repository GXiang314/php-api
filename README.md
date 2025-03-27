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
$app->router->get('/path/{id}', function (Request $req, Response $res) {
    return ['id' => $req->params['id']];
});

# class method
$app->router->post('path', [DemoController::class, 'methodName'])
```

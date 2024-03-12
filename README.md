# PHP simplest API Template

##### quick start:

```shell
composer install
composer start # run php server http://localhost:8000
```

##### api response demo:
http://localhost:8000/api/demo
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
    "message": "ok.",
    "execution_time": 0.0009458065032958984
}
```

##### route register:


```php
# ./src/public/index.php
# callback function
$app->router->get('path/{var}', function() {
    return json_encode(['data' => '1234'])
})

# class method
$app->router->post('path', [DemoController::class, 'methodName'])
```

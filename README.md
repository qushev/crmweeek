# Weeek API Client

PHP-клиент для работы с API [Weeek](https://weeek.net) на GuzzleHttp + Monolog.  
Разработан для использования в MODX Revolution.

## Установка

```bash
php composer.phar require guzzlehttp/guzzle monolog/monolog
```

Разместите класс по пути:

```
core/app/crmweeek/Weeek.php
```

и подключите namespace в `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "Crmweeek\\": "core/app/crmweeek/"
        }
    }
}
```

затем выполните:

```bash
php composer.phar dump-autoload
```

## Способ запуска в сниппите
```php

require_once MODX_BASE_PATH . 'vendor/autoload.php';

// Получение ключа API
$dotenv = Dotenv\Dotenv::createImmutable(MODX_BASE_PATH);
$dotenv->load();

// Подключение к API weeek
$weeek = new Crmweeek\Weeek ($_ENV['W_KEY']);

// print_r($weeek->get('user',['object'=>'me']));
// Получени доски
print_r($weeek->get('tm',['object'=>'boards','query'=>['projectId'=>2]]));

// Получение колонки
print_r($weeek->get('tm',['object'=>'board-columns','query'=>['boardId'=>15]]));

// Получение такска
print_r($weeek->get('tm',['object'=>'tasks','query'=>['boardColumnId'=>51]]));

// Получение организации
print_r($weeek->get('crm',['object'=>'organizations']));

//Создание организации
print_r($weeek->post('crm',[
    'object'=>'organizations',
    'data'  =>[
        'name' => 'ООО Ромашка',
        'addresses' => ['г. Москва, ул. Ленина, 1'],
        'emails' => ['test@example.com'],
        'phones' => ['+79001234567']]
]));
```
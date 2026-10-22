# Weeek API Client

PHP-клиент для работы с API [Weeek](https://weeek.net) на GuzzleHttp + Monolog.  
Разработан для использования в MODX Revolution.

## Требования

- PHP >= 8.1
- Composer
- MODX Revolution (используется константа `MODX_BASE_PATH`)

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

## Быстрый старт

```php
require_once MODX_BASE_PATH . 'vendor/autoload.php';

use Crmweeek\Weeek;

$weeek = new Weeek('your-bearer-token');
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

## Методы

### get(string $method, array $params = []): ?string

GET-запрос к API. Возвращает строку JSON или `null` при ошибке.

| Ключ `$params` | Тип | Описание |
|----------------|-----|----------|
| `object` | `string` | Путь объекта, например `crm/organizations` |
| `id` | `string` | ID ресурса (UUID) |
| `query` | `string` | Поисковая строка |

Примеры:

```php
// Список организаций
$weeek->get('crm/organizations');

// Организация по ID
$weeek->get('crm/organizations', ['id' => '00000000-0000-0000-0000-000000000000']);

// Поиск
$weeek->get('crm/organizations', ['query' => 'ООО Ромашка']);
```

### post(string $method, array $params = []): ?string

POST-запрос. Тело отправляется как JSON (`Content-Type: application/json`).

| Ключ `$params` | Тип | Описание |
|----------------|-----|----------|
| `object` | `string` | Путь объекта, например `crm/organizations` |
| `data` | `array` | Данные для тела запроса |

Пример — создание организации:

```php
$result = $weeek->post('crm/organizations', [
    'data' => [
        'name'         => 'ООО Ромашка',
        'addresses'    => ['г. Москва, ул. Ленина, 1'],
        'emails'       => ['test@example.com'],
        'phones'       => ['+79001234567'],
        'responsibles' => ['00000000-0000-0000-0000-000000000000'],
    ],
]);

$data = json_decode($result, true);
```

## Логирование

Используется [Monolog](https://github.com/Seldaek/monolog).

- Канал: `weeek`
- Уровень: `DEBUG`
- Файл: `logs/weeek.log`

При ошибке API в лог пишутся: тип запроса, метод, переданные данные и исключение.

## Обработка ошибок

При ответах 4xx/5xx и сетевых сбоях Guzzle выбрасывает `RequestException`.  
Ошибка логируется, метод возвращает `null`:

```php
$result = $weeek->get('crm/organizations');

if ($result === null) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Weeek API недоступен');
}
```

## Безопасность

- Не хардкодьте токен — храните его в `.env` (через `vlucas/phpdotenv`) и читайте через `getenv('WEEEK_TOKEN')`.
- Держите папку `logs/` вне веб-рута (`www/`).
- Убедитесь, что у владельца PHP-процесса есть права на запись в `logs/`.

## Ссылки

- [Weeek API](https://weeek.net)
- [GuzzleHttp](https://docs.guzzlephp.org)
- [Monolog](https://github.com/Seldaek/monolog)

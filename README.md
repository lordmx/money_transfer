# Тестовое задание: перевод средст между пользователями

Основные возможности:

 * API-метод для перевода средств от одного пользователя к другому
 * API-метод для получения истории пользовательских операций по кошелькам
 * API-метод для получения текущего баланса пользователей по кошелькам

### Развертывание проекта
* Клонируйте репозиторий
* Выполнить: 
```
$ php composer.phar install
```
* Создайте БД:
```
CREATE DATABASE money_transfer;
```
* Измените настройку проекта (config/config.yml):
```
mysql:
  host: localhost
  user: root
  password:
  schema: money_transfer
```
* Запустите миграции:
```
$ vendor/bin/phpmig migrate
```
### Запуск автотестов
* Настрока (codeception.yml):
```
...
modules:
    enabled:
       - REST:
           depends: PhpBrowser
           url: 'http://api/api/v1/' <- необходимо поменять домен проекта
...           
```
* Запуск:
```
$ vendor\bin\codecept run
```
### Структура проекта
* app - классы инициализации приложения
* config - конфигурация приложения
* migrations - миграции БД
* src - классы проекта
* tests - тесты codeception
* vendor - стронние библиотеки

### API-методы
#### Получить баланс
Запрос:
```
GET /api/v1/balance?wallet_id=1&limit=10&offset=0
```
где:
* wallet_id - идентификатор кошелька
* limit - ограничение сущностей в выборке
* offset - смещение сначала выборки

Ответ:
```
{
  "resultset": {
    "metadata": {
      "limit": 10,
      "offset": 0,
      "count": 2
    }
  },
  "balance": [
    {
      "wallet_id": 1,
      "balance": 150,
      "currency_id": "RUB"
    },
    {
      "wallet_id": 2,
      "balance": 0,
      "currency_id": "RUB"
    }
  ]
}
```
#### Получить историю операций
Запрос:
```
GET /api/v1/transactions?wallet_id=1&date_from=2016-01-01T10:00:00&date_to=2016-01-01T10:00:00:&limit=10&offset=1
```
где:
* wallet_id - идентификатор кошелька
* date_from - дата начала выборки
* date_to - дата окончания выборки
* limit - ограничение сущностей в выборке
* offset - смещение сначала выборки

Ответ:
```
{
  "resultset": {
    "metadata": {
      "limit": 10,
      "offset": 0,
      "count": 3
    }
  },
  "transactions": [
    {
      "id": 1,
      "created_at": "2016-01-01T10:00:00+03:00",
      "amount": 100,
      "wallet_id": 1,
      "document_id": 1,
      "user_id": 1
    },
    {
      "id": 2,
      "created_at": "2016-01-01T10:00:00+03:00",
      "amount": 100,
      "wallet_id": 1,
      "document_id": 1,
      "user_id": 1
    },
    {
      "id": 3,
      "created_at": "2016-01-01T10:00:00+03:00",
      "amount": -50,
      "wallet_id": 1,
      "document_id": 1,
      "user_id": 1
    }
  ]
}
```
#### Перевод средств
Запрос:
```
POST /api/v1/transactions
```
Тело запроса:
* source_wallet_id - идентификатор исходного кошелька
* target_wallet_id - идентификатор целевого кошелька
* target_user_id - идентификатор целевого пользователя
* amount - сумма перевода
* notice - комментарий к переводу

Ответ:
```
{
  "resultset": {
    "metadata": {
      "limit": 0,
      "offset": 0,
      "count": 2
    }
  },
  "transactions": [
    {
      "id": 4,
      "created_at": "2016-09-18T17:23:47+03:00",
      "amount": -110,
      "wallet_id": 1,
      "document_id": 2,
      "user_id": 1
    },
    {
      "id": 5,
      "created_at": "2016-09-18T17:23:47+03:00",
      "amount": 100,
      "wallet_id": 1,
      "document_id": 2,
      "user_id": 2
    }
  ]
}
```

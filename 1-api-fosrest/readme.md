Срез из проекта "fruits-api" (REST-API).

Нам дали готовую базу какого-то легаси проекта, нужно было сделать для нее API. Попросили использовать `knplabs/knp-paginator-bundle` для пагинации и `knplabs/doctrine-behaviors` для локализации проекта. Остальное - на наше усмотрение.

Стек технологий:
 - PostgreSQL
 - Docker
 - JWT

Используемые вендоры:

```json
{
    "brouzie/mailer-bundle": "^0.1.0@dev",
    "doctrine/doctrine-bundle": "^1.6",
    "doctrine/doctrine-migrations-bundle": "^1.3",
    "doctrine/orm": "^2.5",
    "friendsofsymfony/rest-bundle": "^2.2",
    "incenteev/composer-parameter-handler": "^2.0",
    "jms/serializer-bundle": "^2.2",
    "knplabs/doctrine-behaviors": "dev-translatable-column-config",
    "knplabs/knp-paginator-bundle": "^2.6",
    "lexik/jwt-authentication-bundle": "^2.4",
    "nelmio/api-doc-bundle": "^2.0",
    "sensio/distribution-bundle": "^5.0.19",
    "sensio/framework-extra-bundle": "^3.0.2",
    "symfony/monolog-bundle": "^3.1.0",
    "symfony/polyfill-apcu": "^1.0",
    "symfony/swiftmailer-bundle": "^2.3.10",
    "symfony/symfony": "3.3.*",
    "twig/twig": "^1.0||^2.0"
}
```

В примере контроллер CRUD для сущности `Bid` (Объявление о покупке или продаже) - `BidsController`.

Было пожелание от команды фронтенда, отдавать данные как можно более линейно, не делая вложенность. Поэтому в ответе мы отдаем данные через DTO `BidDTO` а не сериализуем сущности напрямую. По этим DTO строится Swagger документация ответов через ApiDoc и формируется демо-страница, через которую можно дергать методы.

Фильтрация коллекции `Bid`s тоже производится посредством DTO `BidsFilterDTO`, данные на который маппятся при помощи формы `BidsFilterForm`. Дальше этот DTO подается на вход в сервисный слой для доступа к данным (`BidsDataFetcher`).

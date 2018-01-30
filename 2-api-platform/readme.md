Срез из проекта "wm-api" (REST-API).

Нам дали еще одну готовую базу какого-то легаси проекта, и снова нужно было сделать для нее API. В этот раз решили попробовать Api-Platform.

Стек технологий:
 - Symfony Flex
 - PostgreSQL
 - Docker
 - JWT
 - Behat

Используемые вендоры:

```json
{
        "api-platform/api-pack": "^1.0",
        "api-platform/core": "dev-master",
        "box/spout": "^2.7",
        "jms/job-queue-bundle": "^1.4",
        "knplabs/doctrine-behaviors": "^1.5",
        "lexik/jwt-authentication-bundle": "^2.4",
        "nelmio/cors-bundle": "^1.5",
        "oro/doctrine-extensions": "^1.2",
        "phpoffice/phpspreadsheet": "^1.0",
        "symfony/apache-pack": "^1.0",
        "symfony/browser-kit": "^3.4",
        "symfony/console": "^3.4",
        "symfony/flex": "^1.0",
        "symfony/framework-bundle": "^3.4",
        "symfony/lts": "^3@dev",
        "symfony/maker-bundle": "^1.0",
        "symfony/orm-pack": "^1.0",
        "symfony/profiler-pack": "^1.0",
        "symfony/web-server-bundle": "^3.4",
        "symfony/yaml": "^3.4",
        "vich/uploader-bundle": "^1.7"
}
```

Особенность Api-Platform заключается в том, что она позволяет толком не писать код. При помощи аннотации `@ApiResource` над сущностью можно реализовать полноценный CRUD без единой строчки кода. Но как всегда, требования бизнеса не ограничиваются простым CRUD.

В примере контроллер **пакетного обновления** для сущности `Price\Item` (Какая-то запись статистики) - `SavePriceItemsBatchAction`.

На вход приходит DTO `UpdatePriceItemsRequest`, состоящий из набора `UpdatePriceItemRequest`. Данные из входящего JSON маппятся автоматом на DTO без нашего участия благодаря Api-Platform. Далее включается наш слушатель валидации `ValidateListener`, которые проверяет аргумент контроллера `$data` перед вызовом контроллера. Если хотя бы в одном наборе данных пакетного обновления есть некорректные значения - контроллер не будет вызван и клиенту будует возвращено внятное сообщение об ошибке (см тест `batch_update.feature`). Пришлось декорировать встроенный `IriConverterInterface` что бы он не кидал исключение, если связанная запись не найдена. Нам нужно возвращать клиенту **все наборы ошибок** а не по одной.
 
В контроллере нет `persist`/`flush`, только вызов метода `getPersistentObjects`, в котором непосредственно перегоняются данные из DTO в сущность. В Api-Platform все, что возвращается из контроллера при вызове `POST`/`PUT` http-запросов - автоматом заперсистится и зафлашится. Правда, такое удобство не работает с массивами, но я без труда исправил и это при помощи `CompositeModelDataPersister`. 

Еще в примере загрузка файлов, для того, что бы протестировать ее, немного дорабатывали контекст Behat.

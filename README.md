# mindbox

Для локальный разработки вам понадобится:
_bash, docker, docker-compose_

Чтобы развернуть у себя и запустить вам нужно выполнить следующие команды:
```shell script
chmod +x run.sh
./run.sh --build
./run.sh -o php-cli php artisan vendor:publish --tag=mindbox-config
```

После этого вы можете поменять настройки config/mindbox.php и посетить https://127.0.0.1:8080/

В файле config/mindbox.php параметры _testUserLogin_ и _testUserPassword_ опциональны и нужны только для автотестов.

Список общих команд:
- **./run.sh --build**     - собрать проект
- **./run.sh --run**       - запустить/перезапустить собранный проект
- **./run.sh --down**      - остановить контейнеры
- **./run.sh --migration** - запустить миграции
- **./run.sh --test**      - запустить тесты

Для просмотра всех параметров, просто запустите **./run.sh** или **./run.sh --help [cmd]**

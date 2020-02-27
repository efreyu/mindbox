#!/usr/bin/env bash
#/usr/bin/env bash

##Variables
if [ "${OS}" == "Windows_NT" ]; then
    OS_TYPE="windows";
    DOCKERCMD="winpty docker-compose.exe"
    DOCKERCORE="winpty docker.exe"
elif [ "${OSTYPE}" == "linux-gnu" ]; then
    OS_TYPE="linux";
    DOCKERCMD="docker-compose"
    DOCKERCORE="docker"
else
    OS_TYPE="macos";
    DOCKERCMD="docker-compose"
    DOCKERCORE="docker"
fi
## Constants
GREEN_OUTPUT='\033[0;32m'
RED_OUTPUT='\033[0;31m'
YELLOW_OUTPUT='\033[1;33m'
CLEAR_OUTPUT='\033[0m'
## Colored output
pc() {
    RES=""
    case "$1" in
        green)
            RES="${GREEN_OUTPUT}$2${CLEAR_OUTPUT}"
            ;;

        red)
            RES="${RED_OUTPUT}$2${CLEAR_OUTPUT}"
            ;;

        yellow)
            RES="${YELLOW_OUTPUT}$2${CLEAR_OUTPUT}"
            ;;

        *)
            RES="${CLEAR_OUTPUT}$2${CLEAR_OUTPUT}"
            ;;

    esac

    printf "${RES}"
}


fix_perm_data() {
    if [ "${OS_TYPE}" != "windows" ]; then
        if [ -d "$(pwd)/bootstrap/cache" ]; then own_commands php-cli chmod -R 777 /var/www/bootstrap/cache; fi; \
        if [ -d "$(pwd)/storage" ]; then own_commands php-cli chmod -R 777 /var/www/storage; fi; \
        if [ -d "$(pwd)/vendor" ]; then own_commands php-cli chmod -R 777 /var/www/vendor; fi; \
        if [ -d "$(pwd)/node_modules" ]; then own_commands php-cli chmod -R 777 /var/www/node_modules; fi; \
        if [ -d "$(pwd)/public/build" ]; then own_commands php-cli chmod -R 777 /var/www/public/build; fi; \
        if [ -d "$(pwd)/hooks" ]; then own_commands php-cli chmod -R 777 /var/www/hooks; fi; \
        if [ -d "$(pwd)/docker/static" ]; then own_commands php-cli chmod -R 777 /var/static; fi; \
    fi
}

fix_perm_db() {
  if [ "${OS_TYPE}" != "windows" ]; then
        if [ -d "$(pwd)/docker/.db" ]; then sudo chown ${USER} $(pwd)/docker/.db -R; fi; \
    fi
}

fix_env_file() {
    if [ ! -f "$(pwd)/env_file" ]; then cp $(pwd)/env_file.example $(pwd)/env_file; fi;
}

fix_symlink() {
    eval ${DOCKERCMD} exec php-cli sh /usr/local/bin/fix_symlink;
}

own_commands() {
    eval ${DOCKERCMD} exec $1 $2 $3 $4 $5 $6 $7 $8
}

migration_commands() {
    case "$2" in

        --rollback|-r)
            eval ${DOCKERCMD} exec php-cli php artisan migrate:rollback
            ;;

        *)
            eval ${DOCKERCMD} exec php-cli php artisan migrate
    esac
}

general_help() {
    case "$1" in
        --build|-b|build|b)
            pc "green" "$0 --build "
            pc "none" "сбилдить все контейнеры, "
            pc "green" "$0 --build container-name "
            pc "none" "для сборки одного контейнера\n"
        ;;
        --run|-r|run|r)
            pc "green" "$0 --run "
            pc "none" "запустить все контейнеры, "
            pc "green" "$0 --run container-name "
            pc "none" "для запуска одного контейнера\n"
        ;;
        --down|-d|down|d)
            pc "green" "$0 --down "
            pc "none" "остановить все контейнеры, "
            pc "green" "$0 --down container-name "
            pc "none" "для остановки одного контейнера\n"
        ;;
        --own|-o|own|o)
            pc "green" "$0 --own container-name cmd"
            pc "none" "выполнить произвольную команду в контейнере\n"
        ;;
        --migration|-m|migration|m)
            pc "green" "$0 --migration "
            pc "none" "выполнить миграцию. Дополнительный аргумент "
            pc "green" "--rollback | -r"
            pc "none" " - откатить миграцию\n"
        ;;
        --remove|remove)
            pc "green" "$0 --remove "
            pc "none" "Удалить все контейнеры внутри системы\n"
        ;;
        *)
            if [ $1 ]; then
                pc "yellow" "Command \"$1\" is not correct.\n"
            else
                pc "yellow" "Nothing caused.\n"
            fi
            pc "green" "Usage: $0 --help {--build|--run|--down|--own|--migration|--remove}\n"
            exit 1
    esac
}

run() {
    fix_env_file
    fix_perm_db
    if [ $1 == 'build' ]; then
        eval ${DOCKERCMD} up -d --build $2 $3
    else
        eval ${DOCKERCMD} up -d $2 $3
    fi
    fix_perm_data
    fix_symlink
    ## Running migration
    $0 -o php-cli php artisan migrate
    ## Running tests
    $0 -t
}


case "$1" in
    --build|-b)
        run 'build' $2 $3
        ;;
    --run|-r)
        run 'run' $2 $3
        ;;
    --down|-d)
        fix_perm_data
        fix_perm_db
        eval ${DOCKERCMD} down
        ;;
    --own|-o)
        own_commands $2 $3 $4 $5 $6 $7 $8
        ;;
    --migration|-m)
        migration_commands $1 $2 $3
        ;;
    --remove)
        eval ${DOCKERCORE} system prune -a
        ;;
    --test|-t)
        eval ${DOCKERCMD} exec php-cli php vendor/bin/phpunit
        ;;
    --help|-h)
        general_help $2 $3
        ;;
    *)
        pc "yellow" "Nothing caused.\n"
        pc "green" "Usage: $0 {--build|--run|--down|--own|--test|--migration|--remove|--help} CMD\n"
        exit 1

esac

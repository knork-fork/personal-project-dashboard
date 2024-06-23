# Personal project dashboard

Personal project dashboard written in PHP and Symfony.

Start container:

```
docker-compose up --build -d
```

By default app should be available @ `localhost:60009`

Run composer install:

```
docker/composer install --no-interaction
```

Run setup script to setup local env variables and add host ssh keys to container (some endpoints may need access to container host):

```
scripts/setup.sh
```

Run migrations:

```
docker/console doctrine:migrations:migrate
```


Or if already built:

```
docker-compose up -d
```

Stop container:

```
docker-compose down
```

## Extras

Enter the shell:

```
docker/shell
```


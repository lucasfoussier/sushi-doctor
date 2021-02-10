# Sushi-doctor #
```
> Start the linter
docker-compose run yarn-dev yarn lint
```
> You can use `// eslint-disable-next-line` to ignore the next line and `/* eslint-disable */` to ignore all warnings in a file.
```
> Update schema with new tables
docker-compose exec php php bin/console app:update-dynamo-db
```
```
> Drop and recreate schema
docker-compose exec php php bin/console app:update-dynamo-db --delete
```
```
> Regenerate JWT certificates (dev)
docker-compose exec php php bin/console lexik:jwt:generate-keypair  --overwrite
```


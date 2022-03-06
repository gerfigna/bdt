#Signaturit Lobby Wars 

Run it on Docker

```
docker-compose up -d
```

Install dependencies

`docker-compose exec fpm composer install`

## Use

###Compare signatures and get the winner
HTTP

`curl http://localhost:8000/api/trial/KNN/NNV/winner`

CLI 

`docker-compose exec fpm bin/console app:trial-winner NVV KV`

## Test
`docker-compose exec fpm /usr/local/bin/composer run-script test`


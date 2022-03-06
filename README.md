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


###Guess the minimum signature required tpo win

HTTP

`curl http://localhost:8000/api/trial/N%23V/NVV/guess-signature`

CLI

`docker-compose exec fpm bin/console app:signature-guess N#V NVV`

## Test
`docker-compose exec fpm /usr/local/bin/composer run-script test`


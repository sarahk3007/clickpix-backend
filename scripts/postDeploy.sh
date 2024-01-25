docker-compose down;

docker-compose run --rm php composer update
docker-compose run --rm php composer install    

docker-compose up -d
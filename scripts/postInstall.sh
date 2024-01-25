# set env vars
set -o allexport; source .env; set +o allexport;

#wait until the server is ready
echo "Waiting for software to be ready ..."
sleep 60s;



docker-compose down

cat << EOT > ./basic/config/db.php 
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mysql;dbname=yii',
    'username' => 'yii',
    'password' => '${DATABASE_PASSWORD}',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

EOT

docker-compose run --rm php composer update --prefer-dist
docker-compose run --rm php composer install    

docker-compose up -d
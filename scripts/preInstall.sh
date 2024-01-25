# set env vars
set -o allexport; source .env; set +o allexport;

chmod -R 777 ./basic/
mv ./basic/config_template ./basic/config
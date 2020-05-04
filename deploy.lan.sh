#! /bin/bash

set -e

php artisan down

cat > .env <<- EOF
APP_ENV=development
APP_KEY=
APP_DEBUG=true
APP_URL=http://api.stop-covid.dev.soyhuce.lan

LOG_CHANNEL=development
ROLLBAR_SERVER_TOKEN=$ROLLBAR_SERVER_TOKEN

DB_CONNECTION=pgsql
DB_HOST=database
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=postgres

REDIS_HOST=redis-server

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=log

APP_TOKEN=u5ag50qVviYq4L20nUdTagBhVajrlVpVc3wqZ37wcKQnQdSqAFZbcfNgecQ9KeyJ
HORIZON_TOKEN=bXX9z5FFVk3z3pnITE1IvzpkYFma4J5AdJrOT9vLAnsWudzLCopVCz2ha4hq
EOF

php artisan key:generate

until redis-cli -h redis-server ping; do
  sleep 1
  echo "waiting redis";
done

until psql -h database -U postgres -c '\l'; do
  sleep 1
  echo "waiting postgres";
done

php artisan migrate:refresh
php artisan db:seed

php artisan config:cache
php artisan route:cache

php artisan horizon:publish
php artisan storage:link

service supervisor start

php artisan up

Start Docker container on PORT=8080

``docker-compose up -d``

``docker exec web-app chmod 777  -R  /var/www/storage``

``docker exec web-app php artisan migrate``
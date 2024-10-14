Ajouter dans le .env les keys de Spotify : 
SPOTIFY_CLIENT_ID='',
SPOTIFY_CLIENT_SECRET=''

composer install 
php artisan migrate
php artisan serve

-> récupération des rappeurs : php artisan fetch:rappers

Проект без интеграции с gotify и MailHog (надо тратить много времени на разворачивание этих сервисов с нуля)
Вся бизнес логика готова (Пример работы http://18.159.146.170:8000)

1. install git
2. install docker & docker-compose
3. git clone https://github.com/ChagarinIvan/ddd.git
4. docker-compose up -d
5. docker-compose exec app composer install
6. docker-compose exec app php artisan migrate
7. docker-compose exec app php artisan queue:work --daemon
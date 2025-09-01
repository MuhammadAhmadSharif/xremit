<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.5.2
Current Version: 2.6.0

Feature Update:
1. Integrate Automatic Currency exchange API (currency layer)
2. A new language (French, Hindi)
3. System Maintenance Mode
4. A payment gateway (PayStack)
5. Installer Update


Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate &&  php artisan passport:install --force

2. To Update Basic Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Update\\BasicSettingsSeeder

3. To Update Language Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\LanguageSeeder

4. To Update System Maintenance Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\SystemMaintenanceSeeder

5. To Update Live Exchange Rate Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\LiveExchangeRateSeeder

6. To Update Payment Gateway Seeder Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\PaymentGatewaySeeder

7. To Update Feature Seeder Seeder Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\UpdateFeatureSeeder




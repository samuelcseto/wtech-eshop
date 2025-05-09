# Vývojárska príručka pre E-shop WTECH

Táto príručka poskytuje informácie potrebné pre nastavenie a spustenie vývojového prostredia pre E-shop WTECH. Príručka je určená pre vývojárov, ktorí chcú pracovať na tomto projekte.

## Požiadavky

-   PHP 8.2 alebo novší
-   Composer 2.x
-   Node.js 18.x alebo novší a npm
-   PostgreSQL 14.x alebo novší
-   Git

## Inštalácia

### 1. Klonovanie repozitára

```bash
git clone https://github.com/samuelcseto/wtech-eshop.git
cd wtech-eshop
```

### 2. Inštalácia PHP závislostí

```bash
composer install
```

### 3. Inštalácia JavaScript závislostí

```bash
npm install
```

### 4. Konfigurácia prostredia

Skopírujte `.env.example` súbor a vytvorte vlastný `.env` súbor:

```bash
cp .env.example .env
```

Upravte `.env` súbor s vašimi lokálnymi nastaveniami, najmä:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=wtech_eshop
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Generovanie aplikačného kľúča

```bash
php artisan key:generate
```

### 6. Vytvorenie databázy

Vytvorte novú PostgreSQL databázu s názvom `wtech_eshop` (alebo s názvom, ktorý ste špecifikovali v `.env` súbore).

### 7. Migrácia a seedovanie databázy

```bash
php artisan migrate --seed
```

Pre vytvorenie administrátorského účtu:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Toto vytvorí administrátorský účet s predvolenými prihlasovacími údajmi:

-   Email: `admin@example.com`
-   Heslo: `password`

### 8. Symbolické prepojenie pre storage

```bash
php artisan storage:link
```

## Spustenie vývojového servera

### 1. Spustenie Laravel servera

```bash
php artisan serve
```

Server bude predvolene dostupný na adrese `http://localhost:8000`

### 2. Spustenie Vite pre frontend development

V novom terminále:

```bash
npm run dev
```

## Testovanie

### Spustenie PHPUnit testov

```bash
php artisan test
```

## Produkčný build

Pre vytvorenie produkčného buildu frontendu:

```bash
npm run build
```

## Debugovanie

### Loggy

Laravel loggy sú uložené v `storage/logs/laravel.log`. Pre zobrazenie loggov v reálnom čase môžete použiť:

```bash
tail -f storage/logs/laravel.log
```

### Tinker

Pre interaktívny shell s prístupom k Laravel frameworku:

```bash
php artisan tinker
```

## Bežné problémy a ich riešenia

### 1. Problém s oprávneniami storage adresára

Ak narazíte na problémy s oprávneniami pre písanie do adresára storage, použite:

```bash
chmod -R 775 storage bootstrap/cache
```

### 2. Vyčistenie cache po zmenách v konfigurácii

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 3. Nedostupné CSS/JS po zmene Vite konfigurácie

Zastavte a reštartujte Vite developerský server:

```bash
npm run dev
```

## Použitie administrátorského rozhrania

Po prihlásení s administrátorským účtom máte prístup k administrátorskému rozhraniu na URL:
`http://localhost:8000/admin`

Tu môžete spravovať produkty, kategórie, objednávky a ďalšie entity e-shopu.

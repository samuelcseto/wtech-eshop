# Dokumentácia E-shopu WTECH

## Zadanie

Cieľom projektu bolo vytvoriť webovú aplikáciu typu e-shop, ktorá komplexne rieši základné prípady použitia pre online obchod. Doménou e-shopu je predaj nábytku a domácich dekorácií. Implementácia zahŕňa funkcionality ako prehliadanie katalógu produktov, vyhľadávanie, filtrovanie, správu nákupného košíka, proces objednávky pre registrovaných aj neregistrovaných zákazníkov, správu používateľských profilov a administrátorské rozhranie pre správu produktov.

Odkaz na GitHub repozitár: [https://github.com/samuelcseto/wtech-eshop](https://github.com/samuelcseto/wtech-eshop)
Posledný relevantný commit: `XX`

Odkaz na Figma prototypy: [WTECH WFS - Figma](https://www.figma.com/design/g4qLPDD5i1t3b8MPd36yez/WTECH-WFS?node-id=0-1&t=ExKTVBDrerTDRvxP-1)

## Diagram fyzického dátového modelu

Aktuálny fyzický dátový model je implementovaný pomocou Laravel migrácií. Nižšie je uvedený diagram, ktorý reflektuje štruktúru databázy po všetkých migráciách.

![ERD](/diagram_final.png)

### Zmeny oproti predchádzajúcej fáze / Diagramu:

Oproti predchádzajúcej fázy boli vykonané nasledujúce kľúčové zmeny a doplnenia v databázovej schéme, aby podporovali rozšírenú funkcionalitu e-shopu:

1.  **Používatelia (`users`):**

    -   Pôvodný stĺpec `name` bol nahradený stĺpcami `first_name` a `last_name` pre detailnejšie uchovávanie mena používateľa (migrácia `2025_05_08_135922_update_users_table_add_name_fields.php`).
    -   Pridaný stĺpec `phone` pre kontaktné údaje (migrácia `2025_05_08_183056_add_phone_to_users_table.php`).
    -   Pridaný booleovský stĺpec `is_admin` na rozlíšenie administrátorov (migrácia `2025_05_09_152957_add_is_admin_to_users_table.php`).
    -   Predošlý diagram zobrazuje stĺpce ako `username`, `is_active`, `is_registered`, `last_login`, ktoré nie sú v aktuálnej Laravel implementácii. Naopak, implementácia obsahuje `email_verified_at` a `remember_token`, ktoré sú štandardom Laravelu.

2.  **Adresy používateľov (`user_addresses`):**

    -   Tabuľka bola zavedená na ukladanie viacerých adries pre jedného používateľa, s možnosťou označenia jednej ako predvolenej (`is_default`). Toto je v súlade s diagramom.

3.  **Produkty (`products`), Kategórie (`categories`), Obrázky produktov (`product_images`), Produkt-Kategória (`product_category`):**

    -   Základná štruktúra týchto tabuliek je štandardná pre e-shop.
    -   Pre kategórie bol pridaný stĺpec `image_url` na uloženie cesty k obrázku kategórie (migrácia `2025_05_06_130606_add_image_url_to_categories_table.php`).

4.  **Krajiny (`countries`) a Poskytovatelia dopravy (`shipping_providers`):**

    -   Tieto tabuľky boli pridané na správu dostupných krajín doručenia a rôznych spôsobov dopravy pre každú krajinu.
    -   `countries` obsahuje ISO kód krajiny, názov a status aktivity.
    -   `shipping_providers` obsahuje názov, popis, status, URL pre sledovanie, metódu výpočtu ceny, fixnú cenu dopravy (`price`) a prepojenie na krajinu (`country_id`).
    -   Pôvodne mal `shipping_providers.country_id` unikátne obmedzenie, ktoré bolo odstránené, aby bolo možné priradiť viacero poskytovateľov dopravy jednej krajine. Pridaný bol stĺpec `price` (migrácia `2025_05_08_230154_update_shipping_providers_remove_unique_constraint.php`).
    -   Predošlý diagram tieto tabuľky (`Country`, `ShippingProvider.country_id`, `ShippingProvider.price`) neobsahoval.

5.  **Objednávky (`orders`) a Položky objednávok (`order_items`):**

    -   Stĺpec `user_id` v tabuľke `orders` bol upravený na `nullable`, aby umožnil objednávky aj pre neregistrovaných (hosťovských) používateľov (migrácia `2025_05_09_112208_make_user_id_nullable_in_orders_table.php`).
    -   Pre uchovanie historických údajov o adrese a kontaktných informáciách pre objednávku (nezávisle od aktuálnych údajov používateľa) boli priamo do tabuľky `orders` pridané stĺpce: `email`, `phone`, `first_name`, `last_name`, `address_line1`, `address_line2`, `city`, `postal_code`, `country`. Stĺpec `shipping_address_id` bol zmenený na `nullable` (migrácia `2025_05_09_112026_add_contact_fields_to_orders_table.php`). Toto je odklon od normalizovanej formy, kde by sa adresa brala len z `user_addresses`, ale zabezpečuje konzistenciu údajov objednávky.
    -   Pridané boli aj stĺpce `order_status` a `payment_status` pre lepšie sledovanie stavu objednávky.
    -   Predošlý diagram: `Order` neobsahuje priame kontaktné a adresné stĺpce, ani `order_status`/`payment_status`. Spolieha sa výlučne na `shipping_address_id`.

6.  **Košík (`carts`) a Položky košíka (`cart_items`):**
    -   Tieto tabuľky boli pridané na implementáciu funkcionality nákupného košíka.
    -   `carts` tabuľka podporuje košíky pre registrovaných používateľov (`user_id`) aj hostí (`session_id`).
    -   Do tabuľky `carts` boli pridané stĺpce pre uchovanie kontaktných údajov, adresy doručenia a vybraného spôsobu dopravy a platby počas checkout procesu (migrácia `2025_05_09_110307_add_checkout_data_to_carts_table.php`).
    -   Predošlý diagram tieto tabuľky neobsahoval.

**Zdôvodnenie zmien:**
Hlavným cieľom zmien bolo umožniť komplexnejšiu funkcionalitu e-shopu, vrátane:

-   **Guest checkout:** Umožnenie nákupu bez registrácie (nullable `user_id` v `orders`, `session_id` v `carts`).
-   **Historická integrita objednávok:** Ukladanie adresy priamo do objednávky zabezpečuje, že sa údaje nezmenia, ak si používateľ neskôr zmení svoju adresu v profile.
-   **Flexibilná správa dopravy:** Umožnenie viacerých spôsobov dopravy pre rôzne krajiny.
-   **Detailnejšie informácie o používateľovi a produktoch.**
-   **Administrátorské funkcie.**
-   **Kompletný proces nákupného košíka**, kde sa dáta z checkoutu ukladajú postupne do košíka pred finálnym vytvorením objednávky.

## Návrhové rozhodnutia

-   **Externé knižnice:**

    -   **Backend:** Použitý bol primárne Laravel framework (`laravel/framework`) a jeho štandardné balíčky.
    -   **Frontend:** Na strane frontendu sa pre buildovanie assetov využíva Vite (`laravel-vite-plugin`, `vite`). Štýlovanie je riešené pomocou Bootstrap s vlastným CSS. Klientské HTTP požiadavky zabezpečuje `axios`. `concurrently` sa používa v `composer.json` pre súčasné spúšťanie viacerých procesov počas vývoja.
    -   **Zdôvodnenie:** Výber knižníc reflektuje štandardný a osvedčený stack pre vývoj moderných Laravel aplikácií, s dôrazom na efektivitu vývoja, testovania a kvalitu kódu. Bootstrap bol zvolený pre rýchle a konzistentné štýlovanie, s prispôsobením pomocou vlastného CSS.

-   **Používateľské roly:**

    -   Implementovaná je jednoduchá schéma rolí pomocou booleovského atribútu `is_admin` v tabuľke `users`.
    -   Prístup k administrátorským častiam aplikácie je chránený pomocou `AdminMiddleware` (`app/Http/Middleware/AdminMiddleware.php`), ktorý overuje, či je prihlásený používateľ administrátor.
    -   Pre jednoduché vytvorenie administrátorského účtu slúži `AdminUserSeeder.php`.
    -   **Zdôvodnenie:** Pre potreby tohto projektu je binárne rozlíšenie (administrátor / bežný používateľ) postačujúce a nevyžaduje komplexnejší systém rolí a oprávnení.

-   **Oprávnenia:**

    -   Špecifický systém oprávnení (permissions) nebol implementovaný.
    -   Riadenie prístupu je riešené na úrovni rolí (admin vs. používateľ) prostredníctvom spomínaného `AdminMiddleware` a štandardných Laravel `auth` a `guest` middleware pre ochranu trás.
    -   **Zdôvodnenie:** Podobne ako pri rolách, detailný systém oprávnení by pridal zbytočnú komplexitu vzhľadom na rozsah projektu. Aktuálne požiadavky sú pokryté existujúcim riešením.

-   **Košík a Checkout pre hostí:**

    -   Tabuľka `carts` má stĺpec `session_id` pre sledovanie košíkov neregistrovaných používateľov a `user_id` (nullable) pre prihlásených.
    -   `CartController::getCart()` dynamicky získava alebo vytvára košík na základe stavu prihlásenia.
    -   Po prihlásení používateľa sa jeho hosťovský košík zlúči s používateľským košíkom pomocou `CartController::mergeCart()`.
    -   Tabuľka `orders` má `user_id` ako nullable a ukladá kontaktné a doručovacie údaje priamo, aby bola zabezpečená integrita historických objednávok aj pre hostí.
    -   Checkout dáta (kontakt, adresa, spôsob dopravy) sa ukladajú postupne do tabuľky `carts` počas prechádzania checkout procesom (`CartController::storeCheckout`).
    -   **Zdôvodnenie:** Umožnenie nákupu bez registrácie zvyšuje používateľský komfort. Ukladanie checkout dát do košíka umožňuje používateľovi vrátiť sa k rozpracovanému checkoutu a zjednodušuje prenos dát do finálnej objednávky.

-   **Vyhľadávanie produktov s diakritikou:**
    -   V `ProductController::index()` sa pri vyhľadávaní používa normalizácia reťazca (odstránenie diakritiky a prevod na malé písmená) a pre databázu PostgreSQL sa využíva funkcia `TRANSLATE` na porovnávanie bez ohľadu na diakritiku.
    -   `$query->whereRaw('TRANSLATE(LOWER(name), \'áäčďéíĺľňóôŕšťúýž\', \'aacdeillnoorstuyz\') LIKE ?', ...)`
    -   **Zdôvodnenie:** Zabezpečuje lepšiu používateľskú experience pri vyhľadávaní produktov v slovenskom jazyku, kde používatelia môžu zadávať dopyty s diakritikou aj bez nej.

## Programovacie prostredie

-   **Framework:** Laravel Framework 12.0.1
-   **Programovací jazyk:** PHP ^8.2
-   **Databáza:** PostgreSQL
-   **Frontend:**
    -   Build tool: Vite
    -   CSS Framework: Bootstrap s vlastným CSS
    -   JavaScript: Vanilla JS, Axios pre AJAX
-   **Vývojové prostredie:** Visual Studio Code

## Stručný opis implementácie vybraných prípadov použitia

-   **Zmena množstva pre daný produkt (v košíku):**

    -   Táto funkcionalita je riešená v `CartController::update()`. Metóda prijíma ID položky košíka a novú kvantitu. Validuje sa, či je kvantita väčšia ako 0 a či je dostatočné množstvo na sklade. Následne sa aktualizuje záznam v tabuľke `cart_items`.
    -   Controller podporuje AJAXové požiadavky, čo umožňuje dynamickú aktualizáciu súhrnu košíka na stránke bez nutnosti jej kompletného znovunačítania.
    -   Na strane frontendu (napr. `resources/views/cart/show.blade.php` a `resources/views/partials/header.blade.php`) sú tlačidlá "+" a "−", ktoré buď priamo odosielajú formulár alebo (v prípade AJAX implementácie v `resources/views/layouts/app.blade.php` v `<script>`) spúšťajú JavaScriptovú funkciu `updateCartItem`, ktorá posiela AJAX požiadavku.

-   **Prihlásenie:**

    -   Proces prihlásenia začína zobrazením formulára cez `LoginController::showLoginForm()`, ktorý renderuje pohľad `resources/views/auth/login.blade.php`.
    -   Po odoslaní formulára metóda `LoginController::login()` validuje e-mail a heslo. Používa `Auth::attempt()` na overenie používateľa.
    -   Pred autentifikáciou sa uloží aktuálne `session_id`. Po úspešnom prihlásení sa regeneruje session a volá sa `CartController::mergeCart()` na zlúčenie hosťovského košíka (ak existoval) s košíkom prihláseného používateľa.
    -   Následne je používateľ presmerovaný na zamýšľanú stránku alebo na domovskú stránku.

-   **Vyhľadávanie:**

    -   Vyhľadávanie produktov je implementované v `ProductController::index()`. Metóda kontroluje prítomnosť parametra `search` v HTTP požiadavke.
    -   Vyhľadávací termín je normalizovaný (malé písmená, odstránenie diakritiky) pomocou privátnej metódy `normalizeString()`.
    -   Databázový dopyt sa zostavuje tak, aby prehľadával stĺpce `name` a `description` v tabuľke `products`. Pre PostgreSQL sa používa `LOWER()` a `TRANSLATE()` na zabezpečenie case-insensitive a accent-insensitive vyhľadávania.
    -   Podporované je aj vyhľadávanie viacerých slov, kde sa každé slovo (s minimálnou dĺžkou) hľadá samostatne.
    -   Výsledky sú potom zobrazené v pohľade `resources/views/products/index.blade.php`.

-   **Pridanie produktu do košíka:**

    -   Funkcionalita je riešená v `CartController::add()`. Metóda prijíma model produktu a požadované množstvo.
    -   Najprv sa validuje, či je množstvo kladné a či je dostatok produktov na sklade.
    -   Získa alebo vytvorí sa košík pre aktuálneho používateľa (registrovaného alebo hosťa) pomocou `getCart()`.
    -   Skontroluje sa, či produkt s rovnakými atribútmi (ak sú definované) už v košíku existuje. Ak áno, aktualizuje sa jeho množstvo. Ak nie, vytvorí sa nová položka `CartItem`.
    -   Používateľ je presmerovaný späť na predchádzajúcu stránku s notifikáciou o úspechu alebo chybe.

-   **Stránkovanie:**

    -   Stránkovanie je implementované vo viacerých controlleroch, napríklad `ProductController::index()` používa `$query->paginate(9)` na zobrazenie 9 produktov na stránku. Podobne `Admin\ProductController::index()` stránkuje výsledky.
    -   V `AppServiceProvider::boot()` je globálne nastavené používanie Bootstrap štýlov pre paginátor (`Paginator::useBootstrap()`).
    -   V Blade pohľadoch (napr. `resources/views/products/index.blade.php`) sa odkazy na stránkovanie generujú pomocou `{{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}`, čím sa zachovávajú aktuálne filtre a parametre dopytu pri prechode medzi stránkami.

-   **Základné filtrovanie (produktov):**
    -   Filtrovanie produktov je implementované v `ProductController::index()`.
    -   **Podľa kategórie:** Ak je v požiadavke parameter `category` (môže byť aj pole IDčiek), produkty sa filtrujú pomocou `whereHas('categories', ...)` tak, aby patrili do vybraných kategórií.
    -   **Podľa ceny:** Ak sú prítomné parametre `price_from` a/alebo `price_to`, dopyt sa obmedzí na produkty v danom cenovom rozsahu.
    -   **Podľa dostupnosti:** Ak je prítomný parameter `in_stock`, zobrazia sa len produkty s `stock_quantity > 0`.
    -   **Zoradenie:** Parameter `sort` umožňuje zoradenie produktov (napr. `newest`, `price-asc`, `price-desc`).
    -   Filtre sú používateľovi prístupné cez formulár v pohľade `resources/views/products/index.blade.php`. Po odoslaní formulára sa stránka znova načíta s aplikovanými filtrami.

## Snímky obrazoviek

-   Detail produktu (`resources/views/products/show.blade.php`)
    ![PRD](/screenshots/product-details.png)

-   Prihlásenie (`resources/views/auth/login.blade.php`)
    ![LGN](/screenshots/login.png)

-   Homepage (`resources/views/landing.blade.php`)
    ![HMPG](/screenshots/home-page-1.png)
    ![HMPG](/screenshots/home-page-2.png)
    ![HMPG](/screenshots/home-page-3.png)
    ![HMPG](/screenshots/home-page-4.png)
    ![HMPG](/screenshots/home-page-5.png)

-   Nákupný košík s vloženým produktom (`resources/views/cart/show.blade.php`)
    ![CIC](/screenshots/cart-item-compact.png)
    ![CI](/screenshots/cart-item.png)

## Vývojárska príručka

Pre informácie o nastavení a spustení vývojového prostredia navštívte [Vývojársku príručku](./docs/VYVOJARSKA_PRIRUCKA.md).

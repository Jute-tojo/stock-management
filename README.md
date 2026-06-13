# Stock Management

Application de gestion de stock avec Laravel 13, Vue 3, Inertia.js v3, Tailwind CSS v4 et shadcn-vue.

## Stack

- **Backend** : Laravel 13.7 / PHP 8.3 / SQLite
- **Frontend** : Vue 3.5 + TypeScript + Inertia.js v3
- **UI** : Tailwind CSS v4 + shadcn-vue (reka-ui) + Lucide Vue
- **Auth** : Laravel Fortify (login, register, 2FA, passkeys, email verification)
- **Build** : Vite 8 + vue-tsc + ESLint + Prettier
- **Routes** : Wayfinder (helpers TypeScript côté frontend : `@/routes/products`, etc.)
- **Logique** : Services (`App\Services\*`), Form Requests (`App\Http\Requests\*`), Policies (`App\Policies\*`)

## Fonctionnalités

| Module | Détail |
|---|---|
| **Products** | CRUD complet, image upload, SKU auto-généré, recherche + pagination, modal create/edit |
| **Categories** | CRUD via modal, suppression bloquée si liée à des produits, compteur de produits |
| **Clients** | CRUD avec recherche (nom/email/téléphone), formulaire inline + tableau paginé |
| **Stock Movements** | Mouvements entrée/sortie/initial, validation stock négatif bloqué, historique avec recherche + pagination |
| **Dashboard** | Cards de navigation vers Products, Clients, Stock |

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
```

## Développement

```bash
composer dev
```

## Structure

```
app/
├── Enums/            # ProductUnit, StockMovement
├── Http/
│   ├── Controllers/  # Product, Client, StockMovement, Category
│   └── Requests/     # Validation + règles métier
├── Models/           # Product, Client, Category, StockMovement, User
├── Policies/         # ProductPolicy, ClientPolicy
├── Services/         # Logique métier (ProductService, etc.)
└── Providers/        # AppServiceProvider (policy registration)

resources/js/
├── components/       # shadcn-vue + métier (ProductDialog, CategoryDialog, ProductSearch)
├── composables/      # useProductForm, useClientForm, useStockMovementForm, useProductSearch
├── pages/            # Product/, Client/, StockMovement/, Dashboard.vue
├── routes/           # Wayfinder auto-generated
└── types/            # TypeScript interfaces
```

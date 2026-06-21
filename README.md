# GoPay UI SDK pour Laravel

SDK officiel GoPay permettant d'intégrer facilement les paiements Mobile Money dans vos applications Laravel.

---

## Fonctionnalités

* Formulaire de paiement prêt à l'emploi
* Intégration Mobile Money
* Validation cryptographique des paiements
* Traitement automatique après paiement
* Insertion automatique de données après paiement
* Mise à jour automatique de données après paiement
* Redirection ou rafraîchissement automatique
* Mode Sandbox pour les tests
* Traitement des paiements en arrière-plan
* Compatible Laravel 12+

---

## Installation

```bash
composer require gopay/gopay-ui
```

Puis :

```bash
php artisan gopay:install
```

Cette commande :

* publie la configuration
* publie les migrations
* exécute les migrations

---

## Configuration

Ajoutez dans votre fichier `.env` :

```env
GOPAY_API_KEY=
GOPAY_PAYOUT_API_KEY=
GOPAY_SECRET_KEY=
GOPAY_ENV=sandbox
```

Valeurs possibles :

```env
GOPAY_ENV=sandbox
```

ou

```env
GOPAY_ENV=production
```

---

## Exemple complet

```php
use App\Models\Order;
use Gopay\GopayUi\DTO\PaymentFormData;
use Gopay\GopayUi\DTO\PaymentInsertAction;
use Gopay\GopayUi\DTO\PaymentUpdateAction;
use Gopay\GopayUi\Enums\PaymentSuccessAction;
use Gopay\GopayUi\GopayUI;

$form = new PaymentFormData(

    amount: 10,

    currency: 'USD',

    phone: '991234567',

    onSuccess: PaymentSuccessAction::GO_TO_URL,

    redirectUrl: '/payment/success?reference={reference}&amount={amount}&currency={currency}',

    formColor: '#262626',

    payBtnLabel: 'Payer maintenant',

    insertActions: [

        new PaymentInsertAction(
            model: Order::class,
            data: [
                'reference' => '{reference}',
                'amount' => '{amount}',
                'currency' => '{currency}',
                'name' => 'Paiement GoPay'
            ]
        )

    ],

    updateActions: [

        new PaymentUpdateAction(
            model: Order::class,
            where: [
                'reference' => '{reference}'
            ],
            data: [
                'status' => 'PAID'
            ]
        )

    ]

);

echo GoPayUI::renderForm($form);
```

---

## Propriétés de PaymentFormData

| Propriété     | Description                      |
| ------------- | -------------------------------- |
| amount        | Montant à payer                  |
| currency      | USD ou CDF                       |
| phone         | Numéro pré-rempli (9 chiffres)   |
| onSuccess     | Action après paiement            |
| redirectUrl   | URL de redirection               |
| formColor     | Couleur principale du formulaire |
| payBtnLabel   | Texte du bouton payer            |
| insertActions | Insertion automatique            |
| updateActions | Mise à jour automatique          |

---

## Placeholders disponibles

Les placeholders suivants peuvent être utilisés dans :

* redirectUrl
* insertActions
* updateActions

```text
{reference}
{amount}
{currency}
```

Exemple :

```php
redirectUrl: '/success?ref={reference}&amount={amount}'
```

---

## Montants minimums

| Devise | Montant minimum |
| ------ | --------------- |
| USD    | 1               |
| CDF    | 500             |

---

## Mode Sandbox

Pour tester votre intégration sans effectuer de véritables paiements :

```env
GOPAY_ENV=sandbox
```

Le formulaire affichera un badge indiquant que vous êtes en environnement de test.

Résultats simulables :

* success
* failed

Pour passer en production :

```env
GOPAY_ENV=production
```

---

## Traitement automatique des paiements en attente

Exécution manuelle :

```bash
php artisan gopay:process-pending
```

Planification recommandée :

```php
Schedule::command('gopay:process-pending')
    ->everyMinute();
```

Cette commande :

* vérifie les transactions en attente
* consulte leur état chez GoPay
* exécute automatiquement les insertions et mises à jour prévues

---

## Désinstallation

```bash
php artisan gopay:uninstall
```

Cette commande :

* annule les migrations du package
* supprime les ressources publiées

---

## Sécurité

Le SDK signe cryptographiquement les informations sensibles du formulaire.

Toute tentative de modification du montant ou de la devise côté client entraîne le rejet de la transaction.

Seuls les paiements validés par GoPay sont traités.

---

## Politique du SDK officiel

Le SDK GoPay est open-source et peut être utilisé, modifié, forké ou redistribué conformément à sa licence.

Toutefois, seules les versions publiées et distribuées par GoPay sont considérées comme des versions officielles.

Les versions modifiées ou redistribuées par des tiers ne sont ni certifiées, ni auditées, ni maintenues par GoPay.

GoPay ne pourra être tenu responsable d'éventuels dysfonctionnements, pertes de données, failles de sécurité ou dommages résultant de l'utilisation de versions modifiées du SDK.

Pour garantir la sécurité et la compatibilité, il est recommandé d'utiliser exclusivement les versions officielles publiées par GoPay.

---

## Support

[gopay@gooomart.com](mailto:gopay@gooomart.com)

---

## Tests

Le SDK inclut des tests automatisés basés sur Pest.

### Installation des dépendances de test

```bash
composer install
```

Installer Pest :

```bash
composer require pestphp/pest --dev
```

Puis :

```bash
php artisan pest:install
```

### Exécuter tous les tests

```bash
./vendor/bin/pest
```

ou

```bash
php artisan test
```

### Structure recommandée

```text
tests/
├── PaymentFormDataTest.php
```

### Couverture recommandée

Les tests doivent couvrir au minimum :

* Validation de PaymentFormData
* Montants minimums (USD / CDF)
* Validation des numéros de téléphone

### Contribution

Toute nouvelle fonctionnalité importante devrait être accompagnée de tests afin de garantir la stabilité du SDK.


---

## Licence

MIT License

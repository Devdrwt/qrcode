# Système de Vérification QR Code

Application Laravel moderne pour la génération et la vérification de codes QR. Système complet avec interface d'administration pour gérer les codes générés et interface publique pour leur vérification.

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Technologies utilisées](#-technologies-utilisées)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du projet](#-structure-du-projet)
- [API](#-api)
- [Sécurité](#-sécurité)
- [Contribution](#-contribution)
- [Licence](#-licence)

## ✨ Fonctionnalités

### Interface d'administration
- ✅ **Génération de codes QR** : Création en masse de codes QR uniques (format PNG)
- ✅ **Tableau de gestion** : Visualisation de tous les codes générés avec leurs images
- ✅ **Statistiques** : Affichage du nombre total de codes, codes utilisés et codes disponibles
- ✅ **Export PDF** : Téléchargement de la liste complète des codes avec leurs QR codes en PDF
- ✅ **Pagination** : Navigation facilitée pour les grandes listes de codes
- ✅ **Téléchargement individuel** : Téléchargement de chaque QR code individuellement

### Interface de vérification
- ✅ **Scanner QR Code** : Utilisation de la caméra pour scanner les codes
- ✅ **Saisie manuelle** : Possibilité de saisir le code manuellement
- ✅ **Vérification en temps réel** : Validation instantanée du code
- ✅ **Historique des scans** : Affichage des codes récemment scannés
- ✅ **Marquage comme utilisé** : Confirmation d'utilisation d'un code valide
- ✅ **Interface responsive** : Optimisée pour mobile et desktop

### Sécurité et confidentialité
- ✅ **Aucune donnée personnelle** : Seuls les codes et leur statut sont stockés
- ✅ **Codes uniques** : Génération de codes aléatoires de 12 caractères
- ✅ **Protection CSRF** : Protection contre les attaques CSRF
- ✅ **Validation des données** : Validation stricte des entrées utilisateur

## 🛠 Technologies utilisées

### Backend
- **Laravel 10** : Framework PHP moderne
- **PHP 8.1+** : Langage de programmation
- **SQLite** : Base de données (peut être changée pour MySQL/PostgreSQL)
- **Endroid QR Code** : Bibliothèque de génération de QR codes (format PNG)
- **DomPDF** : Génération de fichiers PDF

### Frontend
- **Tailwind CSS** : Framework CSS utilitaire
- **Vite** : Build tool moderne
- **JavaScript Vanilla** : Pas de framework JS lourd
- **HTML5 Camera API** : Pour le scanner QR code

## 📦 Prérequis

- PHP >= 8.1
- Composer
- Node.js >= 16.x et npm
- SQLite (ou MySQL/PostgreSQL)
- Serveur web (Apache/Nginx) ou PHP built-in server

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Devdrwt/Qr_code_projet.git
cd Qr_code_projet
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances Node.js

```bash
npm install
```

### 4. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurer la base de données

**Option A : SQLite (par défaut)**
```bash
touch database/database.sqlite
```

**Option B : MySQL/PostgreSQL**

Modifiez le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qr_code_db
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Exécuter les migrations

```bash
php artisan migrate
```

### 7. Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

### 8. Compiler les assets

**Pour le développement :**
```bash
npm run dev
```

**Pour la production :**
```bash
npm run build
```

### 9. Configurer APP_URL (important)

Dans le fichier `.env`, configurez l'URL de votre application :

```env
APP_URL=http://localhost/Qr-code/public
```

Ajustez selon votre configuration (XAMPP, WAMP, serveur local, etc.)

## ⚙️ Configuration

### Variables d'environnement importantes

```env
APP_NAME="QR Code Verification System"
APP_URL=http://localhost/Qr-code/public
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=sqlite
# ou pour MySQL :
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=qr_code_db
# DB_USERNAME=root
# DB_PASSWORD=
```

## 📖 Utilisation

### Accès aux interfaces

- **Page de vérification** : `http://localhost/Qr-code/public/`
- **Interface d'administration** : `http://localhost/Qr-code/public/admin/codes`

### Génération de codes QR

1. Accédez à la page d'administration (`/admin/codes`)
2. Entrez le nombre de codes à générer (ex: 200)
3. Cliquez sur le bouton "Générer"
4. Les codes sont générés et ajoutés au tableau
5. Vous pouvez :
   - Visualiser tous les codes avec leurs images QR
   - Télécharger un QR code individuel
   - Exporter tous les codes en PDF

### Vérification de codes

1. Accédez à la page principale (`/`)
2. **Option 1 - Scanner** :
   - Cliquez sur "Scanner QR Code"
   - Autorisez l'accès à la caméra
   - Pointez vers le QR code
3. **Option 2 - Saisie manuelle** :
   - Entrez le code dans le champ de saisie
   - Cliquez sur "Vérifier"
4. Le système affiche :
   - ✅ **Code valide** : Le code n'a pas encore été utilisé
   - ⚠️ **Code déjà utilisé** : Le code a déjà été vérifié
   - ❌ **Code invalide** : Le code n'existe pas dans la base
5. Si le code est valide, vous pouvez le marquer comme utilisé

## 📁 Structure du projet

```
Qr_code_projet/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CodeController.php      # Gestion des codes (génération, export)
│   │   │   └── VerifyController.php    # Vérification des codes
│   │   └── Middleware/
│   └── Models/
│       └── Code.php                    # Modèle Code
├── database/
│   ├── migrations/
│   │   └── 2024_01_01_000001_create_codes_table.php
│   └── database.sqlite
├── public/
│   ├── storage/                        # Lien symbolique vers storage/app/public
│   │   └── qrcodes/                    # QR codes générés (PNG)
│   └── index.php
├── resources/
│   ├── views/
│   │   ├── codes/
│   │   │   ├── index.blade.php         # Interface d'administration
│   │   │   └── pdf.blade.php          # Template PDF
│   │   ├── verify/
│   │   │   └── index.blade.php        # Interface de vérification
│   │   └── layouts/
│   │       └── app.blade.php          # Layout principal
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                        # Routes web
│   └── api.php                        # Routes API
├── storage/
│   └── app/
│       └── public/
│           └── qrcodes/               # QR codes stockés
└── .env
```

## 🔌 API

### Vérifier un code

```http
GET /api/verify/{code}
```

**Réponse :**
```json
{
    "status": "valid",
    "message": "Code valide",
    "code": "ABC123XYZ456"
}
```

**Statuts possibles :**
- `valid` : Code valide et non utilisé
- `used` : Code déjà utilisé
- `invalid` : Code inexistant

### Marquer un code comme utilisé

```http
POST /mark-used
Content-Type: application/json

{
    "code": "ABC123XYZ456"
}
```

**Réponse :**
```json
{
    "status": "success",
    "message": "Code marqué comme utilisé"
}
```

## 🔒 Sécurité

- ✅ **Protection CSRF** : Toutes les requêtes POST sont protégées
- ✅ **Validation des données** : Validation stricte des entrées
- ✅ **Aucune donnée personnelle** : Conforme RGPD
- ✅ **Codes uniques** : Génération sécurisée de codes aléatoires
- ✅ **Sanitization** : Nettoyage des données utilisateur

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Fork le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👤 Auteur

**Devdrwt**

- GitHub: [@Devdrwt](https://github.com/Devdrwt)

## 🙏 Remerciements

- Laravel Framework
- Endroid QR Code
- DomPDF
- Tailwind CSS
- Tous les contributeurs open source

---

⭐ Si ce projet vous a été utile, n'hésitez pas à lui donner une étoile !


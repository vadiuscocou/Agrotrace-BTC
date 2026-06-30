AgroTrace BTC

Connectez votre portefeuille à la terre. Tracez chaque Satoshi jusqu'aux champs verdoyants.

AgroTrace BTC est une plateforme novatrice qui permet à la diaspora et aux investisseurs du monde entier de financer directement des coopératives agricoles au Bénin, en utilisant la puissance et la rapidité du réseau Bitcoin Lightning.


Partie 1 : Comment fonctionne la plateforme ? (Guide Investisseur)

Notre objectif est de rendre l'investissement agricole simple, transparent et rentable pour n'importe qui, même sans connaissances techniques poussées. Voici comment cela fonctionne en 4 étapes simples :

1. Choisissez un projet qui a du sens
Parcourez notre catalogue de projets agricoles (par exemple : Plantation de Tomates Bio, Coopérative de Maïs). Chaque projet affiche clairement son objectif financier, sa localisation au Bénin et le retour sur investissement estimé (généralement autour de +30%).

2. Investissez instantanément avec Bitcoin
Une fois le projet choisi, vous pouvez investir la somme de votre choix. Le paiement s'effectue via le Lightning Network de Bitcoin. C'est instantané, sécurisé, et les frais de transfert sont quasiment nuls (frais fixes de plateforme de 2%). Fini les virements internationaux lents et coûteux !

3. Suivez l'avancement en toute transparence (Le cœur de la confiance)
C'est ici qu'AgroTrace se démarque. L'argent n'est pas envoyé "à l'aveugle". 
Le projet est divisé en jalons (étapes de travail, par exemple : Achat des semences, Récolte). À chaque étape, la coopérative doit fournir des preuves (photos, factures) sur la plateforme.
Une fois la preuve validée par nos équipes, elle est gravée pour toujours sur la blockchain Bitcoin (via la fonction OP_RETURN). Personne ne pourra jamais la falsifier. Vous avez la garantie absolue que l'argent sert à travailler la terre.

4. Récoltez vos bénéfices
Une fois le projet terminé et la récolte vendue sur le marché local, les bénéfices sont calculés. Vous recevrez automatiquement votre capital de départ ainsi que vos intérêts (dividendes) directement sur votre portefeuille Lightning.

Le Contrat Numérique et les Garanties
Pour protéger toutes les parties prenantes, AgroTrace intègre un système de contrat d'engagement numérique :
- Un modèle "Crowdfunding" : Le contrat lie l'agriculteur d'un côté, et la plateforme AgroTrace de l'autre (qui représente légalement le regroupement des multiples investisseurs du projet). 
- Génération automatique & Signature : À la seconde même où une coopérative crée un projet sur la plateforme, un contrat est généré. La plateforme crée une empreinte cryptographique (Hash) basée sur les données du projet. Ce Hash fait office de signature numérique irrévocable pour l'agriculteur.
- Répartition équitable : Le contrat stipule clairement la règle d'or d'AgroTrace : 70% des revenus vont à l'agriculteur, et 30% sont redistribués aux investisseurs. Il prévoit également des clauses de force majeure (catastrophes naturelles) pour sécuriser le modèle.

---

Partie 2 : Guide Technique (Installation et Démarrage)

Cette section est destinée aux développeurs et aux juges du Hackathon souhaitant faire tourner le projet sur leur machine locale.

1. Prérequis
- PHP 8.2 ou supérieur
- Composer (Gestionnaire de paquets PHP)
- Node.js & NPM (Pour compiler le design Tailwind)
- MySQL (Via XAMPP, WAMP, Laravel Herd ou autre)

2. Cloner et configurer le projet
Ouvrez un terminal et exécutez les commandes suivantes :

Cloner le dépôt :
git clone <URL_DU_DEPOT>
cd AgroTraceLaravel

Installer les dépendances PHP :
composer install

Installer les dépendances Frontend (Tailwind CSS) :
npm install

Créer le fichier d'environnement et générer la clé de sécurité :
cp .env.example .env
php artisan key:generate


3. Base de données et Fausses Données (Seeding)
1. Créez une base de données MySQL vierge nommée agrotrace.
2. Ouvrez le fichier .env à la racine du projet et vérifiez vos identifiants de base de données (par défaut DB_USERNAME=root et DB_PASSWORD= vide).
3. Construisez la base de données et remplissez-la avec nos données de test (Investisseurs, Coopératives et Projets au Bénin) avec la commande suivante :
php artisan migrate:fresh --seed


4. Démarrer l'application
Pour que l'interface s'affiche correctement, vous devez d'abord compiler le design avec la commande :
npm run build

(Note : Si vous souhaitez modifier le code en direct, utilisez plutôt npm run dev dans un terminal séparé).

Enfin, lancez le serveur web local avec la commande :
php artisan serve

Le projet est maintenant accessible sur http://localhost:8000.

Comment se connecter pour tester ?
Grâce à la commande de "seeding" effectuée à l'étape 3, des comptes de test sont déjà créés :
- Admin : admin@agrotrace.com / Mot de passe : password
- Investisseur : investor@agrotrace.com / Mot de passe : password
- Coopérative : coop@agrotrace.com / Mot de passe : password

(Vous pouvez également créer de nouveaux comptes librement via la page "S'inscrire").

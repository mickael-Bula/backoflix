# Une vraie fausse programmation pour le site O'flix

## La raison de cette appli

Si comme moi tu en as assez de voir sur ton magnifique site de cinéma des affiches bidons et des acteurs anomymes, cette appli est faite pour toi !

Le principe est simple, il s'agit de se connecter sur l'api d'un site qui recence de vrais films et séries pour en récupérer toutes les infos.

## Avant de commencer

Il te faut dans un premier temps suivre les instructions sur le site [OMDb API] (http://www.omdbapi.com/apikey.aspx) afin de récupérer une clé valide de l'API.

Une fois celle-ci en ta possession, tu peux cloner ce projet et lancer son installation. Pour rappel :

```bash
git clone git@github.com:O-clock-Yuna/backoflix-mickael-Bula.git
```

```bash
composer install
```

## Configuration du fichier .env.local

Il faut y renseigner les données de connection à une BDD existante ou, à défaut, la créer.

Pour connaître la version de ton moteur sql :

```bash
mysql -V
```

Petite nouveauté, il faut y ajouter une variable d'environnement `APIKEY={YOUR KEY}`, en veillant à remplacer `{YOUR KEY}` par la valeur de la clé que tu as reçue précédemment.

Le contenu de `.env.local` doit donc ressembler à ceci :

```ini
DATABASE_URL="mysql://xxxxx:xxxxx@127.0.0.1:3306/xxxxx?serverVersion=mariadb-xx.x.xx"
APIKEY="xxxxx"
```

## Lancement d'un serveur local

```bash
php -S localhost:8080 -t public
```

## charger les données des films

Sur la page `/movie/add` il te suffit de saisir un titre de film (**in english please !**) pour que celui-ci s'affiche (s'il existe bien sûr :wink:).

Si le titre te plaît tu peux cliquer sur le bouton `ajouter` et l'appli se charge de tout le reste !

## Voir le résultat

Tu peux maintenant te rendre sur le site O'flix. Si ton site est bien configurée sur la BDD qui vient d'être alimentée, tu devrais normalement retrouver les films et séries que tu viens d'ajouter :grinning:

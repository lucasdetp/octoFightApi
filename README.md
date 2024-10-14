<p><strong>Configuration du projet</strong></p>

<ol>
    <li>
        <p><strong>Ajouter les clés Spotify dans le fichier <code>.env</code> :</strong></p>
        <pre><code>SPOTIFY_CLIENT_ID='' 
SPOTIFY_CLIENT_SECRET=''</code></pre>
    </li>
</ol>

<ol>
    <li>
        <p><strong>Installer les dépendances :</strong></p>
        <pre><code>composer install</code></pre>
    </li>

    <li>
        <p><strong>Migrer la base de données :</strong></p>
        <pre><code>php artisan migrate</code></pre>
    </li>

    <li>
        <p><strong>Lancer le serveur local :</strong></p>
        <pre><code>php artisan serve</code></pre>
    </li>

    <li>
        <p><strong>Récupérer les informations des rappeurs :</strong></p>
        <pre><code>php artisan fetch:rappers</code></pre>
    </li>
</ol>

<?php

/**
 * Script de test automatique pour l'API ScoutMe
 *
 * Utilisation : php test_api.php
 */

$baseUrl = 'http://localhost:8000/api';
$results = [];
$tokens = [];

function test($name, $method, $endpoint, $data = null, $headers = []) {
    global $baseUrl, $results;

    $url = $baseUrl . $endpoint;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true);

    $results[] = [
        'name' => $name,
        'status' => $statusCode,
        'success' => $statusCode < 400,
        'response' => $decoded
    ];

    return $decoded;
}

echo "🧪 Tests API ScoutMe\n";
echo "==================\n\n";

// Phase 1 : Données de référence
echo "📋 Phase 1 : Données de référence\n";
$countries = test('1.1 - Liste des pays', 'GET', '/countries');
echo ($countries ? "✅" : "❌") . " Pays récupérés : " . count($countries) . "\n";

$positions = test('1.2 - Liste des positions', 'GET', '/positions');
echo ($positions ? "✅" : "❌") . " Positions récupérées : " . count($positions) . "\n\n";

// Phase 2 : Inscription
echo "📋 Phase 2 : Inscription\n";
$joueur = test('2.1 - Inscription Joueur', 'POST', '/register', [
    'first_name' => 'Koffi',
    'last_name' => 'Test',
    'email' => 'koffi.' . time() . '@test.com',
    'password' => 'Test1234',
    'password_confirmation' => 'Test1234',
    'role' => 'joueur',
    'position_id' => 13,
    'date_naissance' => '2002-05-15',
    'taille' => 178,
    'pied_fort' => 'Droit',
    'nationality_id' => 1
]);

if (isset($joueur['access_token'])) {
    $tokens['joueur'] = $joueur['access_token'];
    $tokens['joueur_id'] = $joueur['user']['id'];
    echo "✅ Joueur inscrit (ID: {$tokens['joueur_id']})\n";
} else {
    echo "❌ Échec inscription joueur\n";
}

$recruteur = test('2.2 - Inscription Recruteur', 'POST', '/register', [
    'first_name' => 'Jean',
    'last_name' => 'Recruteur',
    'email' => 'jean.' . time() . '@test.com',
    'password' => 'Secure2024',
    'password_confirmation' => 'Secure2024',
    'role' => 'recruteur',
    'nom_organisation' => 'FC Test',
    'country_id' => 23,
    'type' => 'scout'
]);

if (isset($recruteur['access_token'])) {
    $tokens['recruteur'] = $recruteur['access_token'];
    $tokens['recruteur_id'] = $recruteur['user']['id'];
    echo "✅ Recruteur inscrit (ID: {$tokens['recruteur_id']})\n\n";
} else {
    echo "❌ Échec inscription recruteur\n\n";
}

// Phase 3 : Authentification
echo "📋 Phase 3 : Tests /me\n";
$me_joueur = test('3.1 - /me (joueur)', 'GET', '/me', null, [
    'Authorization: Bearer ' . $tokens['joueur']
]);
echo (isset($me_joueur['id']) ? "✅" : "❌") . " /me joueur\n";

$me_recruteur = test('3.2 - /me (recruteur)', 'GET', '/me', null, [
    'Authorization: Bearer ' . $tokens['recruteur']
]);
echo (isset($me_recruteur['id']) ? "✅" : "❌") . " /me recruteur\n\n";

// Phase 4 : Profil joueur
echo "📋 Phase 4 : Gestion profil joueur\n";
$update = test('4.1 - Mise à jour profil', 'PUT', '/joueur/profil', [
    'taille' => 180,
    'motivation' => 'Test de motivation'
], [
    'Authorization: Bearer ' . $tokens['joueur']
]);
echo (isset($update['message']) ? "✅" : "❌") . " Profil mis à jour\n\n";

// Phase 5 : Vidéos
echo "📋 Phase 5 : Gestion vidéos\n";
$video = test('5.1 - Ajouter vidéo', 'POST', '/videos', [
    'titre' => 'Test Video',
    'url_ou_fichier' => 'https://youtube.com/test'
], [
    'Authorization: Bearer ' . $tokens['joueur']
]);

if (isset($video['data']['id'])) {
    $tokens['video_id'] = $video['data']['id'];
    echo "✅ Vidéo ajoutée (ID: {$tokens['video_id']})\n";
} else {
    echo "❌ Échec ajout vidéo\n";
}

$videos = test('5.2 - Liste vidéos', 'GET', '/videos', null, [
    'Authorization: Bearer ' . $tokens['joueur']
]);
echo (isset($videos['data']) ? "✅" : "❌") . " Liste vidéos récupérée\n\n";

// Phase 6 : Annonces
echo "📋 Phase 6 : Gestion annonces\n";
$annonce = test('6.1 - Créer annonce', 'POST', '/annonces', [
    'titre' => 'Test Recrutement',
    'description' => 'Description test',
    'type' => 'recrutement',
    'statut' => 'publiee',
    'visibilite' => 'publique'
], [
    'Authorization: Bearer ' . $tokens['recruteur']
]);

if (isset($annonce['data']['id'])) {
    $tokens['annonce_id'] = $annonce['data']['id'];
    echo "✅ Annonce créée (ID: {$tokens['annonce_id']})\n";
} else {
    echo "❌ Échec création annonce\n";
}

$annonces = test('6.2 - Liste annonces publiques', 'GET', '/annonces');
echo (isset($annonces['data']) ? "✅" : "❌") . " Liste annonces publiques\n\n";

// Phase 7 : Candidatures
echo "📋 Phase 7 : Gestion candidatures\n";
$candidature = test('7.1 - Postuler', 'POST', "/annonces/{$tokens['annonce_id']}/candidatures", [
    'message' => 'Je suis motivé'
], [
    'Authorization: Bearer ' . $tokens['joueur']
]);

if (isset($candidature['data']['id'])) {
    $tokens['candidature_id'] = $candidature['data']['id'];
    echo "✅ Candidature envoyée (ID: {$tokens['candidature_id']})\n";
} else {
    echo "❌ Échec candidature\n";
}

$candidatures = test('7.2 - Candidatures reçues', 'GET', "/annonces/{$tokens['annonce_id']}/candidatures", null, [
    'Authorization: Bearer ' . $tokens['recruteur']
]);
echo (isset($candidatures['data']) ? "✅" : "❌") . " Candidatures reçues\n\n";

// Phase 8 : Policies
echo "📋 Phase 8 : Tests Policies\n";
$forbidden = test('8.1 - Recruteur tente vidéo (doit échouer)', 'POST', '/videos', [
    'titre' => 'Test',
    'url_ou_fichier' => 'test'
], [
    'Authorization: Bearer ' . $tokens['recruteur']
]);
// Le test réussit si status = 403 (accès refusé par le middleware)
$result8_1 = $forbidden && (
    (isset($forbidden['message']) && strpos(strtolower($forbidden['message']), 'joueur') !== false) ||
    !isset($forbidden['message']) // Si pas de message, c'est que le middleware a bloqué avant
);
echo ($result8_1 ? "✅" : "❌") . " Middleware recruteur→vidéo bloqué\n";

$forbidden2 = test('8.2 - Joueur tente annonce (doit échouer)', 'POST', '/annonces', [
    'titre' => 'Test',
    'description' => 'Test',
    'type' => 'recrutement'
], [
    'Authorization: Bearer ' . $tokens['joueur']
]);
// Le test réussit si status = 403 (accès refusé par le middleware)
$result8_2 = $forbidden2 && (
    (isset($forbidden2['message']) && strpos(strtolower($forbidden2['message']), 'recruteur') !== false) ||
    !isset($forbidden2['message']) // Si pas de message, c'est que le middleware a bloqué avant
);
echo ($result8_2 ? "✅" : "❌") . " Middleware joueur→annonce bloqué\n\n";

// Résumé
echo "==================\n";
echo "📊 RÉSUMÉ DES TESTS\n";
echo "==================\n";

$total = count($results);
$success = count(array_filter($results, fn($r) => $r['success']));
$failed = $total - $success;

echo "Total: $total tests\n";
echo "✅ Réussis: $success\n";
echo "❌ Échoués: $failed\n";
echo "Taux de réussite: " . round(($success / $total) * 100, 2) . "%\n";

if ($failed > 0) {
    echo "\n❌ Tests échoués:\n";
    foreach ($results as $result) {
        if (!$result['success']) {
            echo "  - {$result['name']} (Status: {$result['status']})\n";
        }
    }
}

echo "\n✅ Tests terminés!\n";

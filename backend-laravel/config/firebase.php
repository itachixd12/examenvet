<?php

return [
    /**
     * Configuración de Firebase para PetCare Clínica Veterinaria
     * 
     * Reemplaza las credenciales con las de tu proyecto Firebase
     */
    'project_id' => env('FIREBASE_PROJECT_ID', 'petcare-clinica'),
    'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
    'private_key' => env('FIREBASE_PRIVATE_KEY'),
    'client_email' => env('FIREBASE_CLIENT_EMAIL'),
    'client_id' => env('FIREBASE_CLIENT_ID'),
    'auth_uri' => env('FIREBASE_AUTH_URI', 'https://accounts.google.com/o/oauth2/auth'),
    'token_uri' => env('FIREBASE_TOKEN_URI', 'https://oauth2.googleapis.com/token'),
    'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_X509_CERT_URL', 'https://www.googleapis.com/oauth2/v1/certs'),
    'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
    
    /**
     * Mapbox Token para geolocalización de la clínica
     */
    'mapbox_token' => env('MAPBOX_TOKEN', 'pk.eyJ1IjoiZXJpY2tzdGV2ZW4xNyIsImEiOiJjbWl6M25jcjgwbTJ4M2tweTJ5dXEzc29iIn0.gCKA1vCnL0A1rY2qSL3uqQ'),
];

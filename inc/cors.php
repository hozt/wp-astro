<?php

// Add CORS headers to allow cross-origin requests
// Needed for JWT authentication
function add_cors_http_header() {
    // Build allowed origins from theme settings + localhost for dev
    $allowed_origins = ['http://localhost:4321', 'http://localhost:8000'];

    $website_url = get_theme_mod('website_url');
    if ($website_url && filter_var($website_url, FILTER_VALIDATE_URL)) {
        $allowed_origins[] = rtrim($website_url, '/');
    }

    /**
     * Filter the allowed CORS origins.
     *
     * @param array $allowed_origins List of allowed origin URLs.
     */
    $allowed_origins = apply_filters('wp_astro_cors_origins', $allowed_origins);

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    $allowed = in_array($origin, $allowed_origins, true);

    if ($allowed) {
        header("Access-Control-Allow-Origin: $origin");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
        header('Access-Control-Allow-Credentials: true');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if ($allowed) {
            header('Access-Control-Max-Age: 1728000');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            exit(0);
        } else {
            header("HTTP/1.1 403 Access Forbidden");
            exit();
        }
    }
}

add_action('init', 'add_cors_http_header');

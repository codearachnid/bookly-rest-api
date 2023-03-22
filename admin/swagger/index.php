<div id="swagger-ui"></div>

<script>
    window.onload = function() {
        // Begin Swagger UI call region
        const ui = SwaggerUIBundle({
            url: "<?php echo BOOKLY_REST_API_PLUGIN_URI; ?>admin/swagger.php",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            requestInterceptor: (req) => {
                if (! req.loadSpec) {
                    // Header added to authenticate the user using the WP noance
                    // This is necessary when the user interacts with the API through the WP back
                    // panel. Without the nonce code, WP doesn't detect the user authenticated and
                    // rejects the requests.
                    req.headers['X-WP-Nonce'] = wpApiSettings.nonce;
                }
                return req;
            },
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        })
        // End Swagger UI call region

        window.ui = ui
    }
</script>

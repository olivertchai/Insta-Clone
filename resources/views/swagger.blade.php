<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaClone API Docs</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #fafafa;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        window.onload = function () {
            window.ui = SwaggerUIBundle({
                url: "{{ asset('docs/openapi.json') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                docExpansion: 'none',
                displayRequestDuration: true,
                persistAuthorization: true
            });
        };
    </script>
</body>
</html>

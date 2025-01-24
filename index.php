<?php
// Asigna nombres a los IDs de Telegram
$usernames = [
    "690137324" => "ruben.cuello",
    "279601369" => "adrian.sanchez"
];

// Endpoint para procesar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene y valida el ID del usuario enviado desde el cliente
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $input['user_id'] ?? null;

    // Respuesta con el nombre correspondiente o "Guest" si no coincide
    $response = [
        'username' => $user_id && isset($usernames[$user_id]) ? $usernames[$user_id] : 'No permitido',
        'is_authorized' => $user_id && isset($usernames[$user_id]) // Devuelve si el ID es válido
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram MiniApp</title>
    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .welcome {
            text-align: center;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .welcome h1 {
            color: #333;
        }
        .buttons {
            margin-top: 20px;
        }
        .buttons button {
            margin: 5px;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="welcome" id="welcome-container">
        <h1>Welcome!</h1>
        <p id="username">Loading...</p>
        <div class="buttons" id="buttons-container" style="display: none;">
            <button id="btn-fichar">Fichar</button>
            <button id="btn-calendario">Calendario</button>
            <button id="btn-comprobar">Comprobar</button>
        </div>
    </div>

    <script>
        // Inicializar el objeto WebApp de Telegram
        const tg = window.Telegram.WebApp;
        tg.ready();

        // Obtener datos del usuario
        // Obtener datos del usuario
        const user = tg.initDataUnsafe.user;

        // Validar si se obtuvo un ID de usuario
        if (user && user.id) {
            // Enviar el ID al servidor para verificar
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: user.id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.is_authorized) {
                    // Mostrar el nombre de usuario y los botones si el usuario está autorizado
                    document.getElementById('username').textContent = `Hello, ${data.username}!`;
                    document.getElementById('buttons-container').style.display = 'block';
                } else {
                    // Ocultar el contenedor si el usuario no está autorizado
                    document.getElementById('welcome-container').style.display = 'none';
                    alert('Usuario no permitido'); // Puedes mostrar un mensaje emergente si lo deseas
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
                document.getElementById('username').textContent = 'Error loading username.';
            });
        } else {
            // Ocultar el contenedor si no hay un usuario válido
            document.getElementById('welcome-container').style.display = 'none';
            alert('Usuario no permitido'); // Opcional
        }



        // Manejar eventos de los botones
        document.getElementById('btn-fichar').addEventListener('click', () => {
            alert('Fichar clicked!');
        });

        document.getElementById('btn-calendario').addEventListener('click', () => {
            alert('Calendario clicked!');
        });

        document.getElementById('btn-comprobar').addEventListener('click', () => {
            alert('Comprobar clicked!');
        });
    </script>
</body>
</html>

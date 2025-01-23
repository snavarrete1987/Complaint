<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $complaint = htmlspecialchars($_POST['complaint']);

    // Procesar la foto si se subió
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoData = base64_encode(file_get_contents($photoTmpPath));
        $photoType = $_FILES['photo']['type'];
        $photoAttachment = "data:$photoType;base64,$photoData";
    } else {
        $photoAttachment = null;
    }

    // Configurar el correo
    $to = "snavarrete.it@gmail.com"; // Cambia esto por tu correo
    $subject = "Nueva Queja de $name";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Construir el cuerpo del correo
    $message = "
        <html>
        <body>
            <h2>Detalles de la Queja</h2>
            <p><strong>Nombre:</strong> $name</p>
            <p><strong>Teléfono:</strong> $phone</p>
            <p><strong>Descripción:</strong> $complaint</p>";

    if ($photoAttachment) {
        $message .= "<p><strong>Foto adjunta:</strong></p>
                     <img src='$photoAttachment' alt='Foto de la queja' style='max-width:300px;'>";
    }

    $message .= "</body></html>";

    // Enviar el correo
    if (mail($to, $subject, $message, $headers)) {
        echo "¡Queja enviada con éxito!";
    } else {
        echo "Hubo un error al enviar el correo. Por favor, intenta de nuevo.";
    }
} else {
    echo "Método no permitido.";
}
?>

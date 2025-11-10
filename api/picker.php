<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-store');
include("index.php");
use Medoo\Medoo;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//print_r($_GET);

if($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['act']))	{	$accao="list";	}
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['act']))	    {	$accao=$_GET['act'];	}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act']))   {	$accao=$_POST['act'];	}


/*

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

*/
//die(json_encode($_POST));

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST' && $accao=="conferDoc"){
 //$barcode=$_POST['barcode'] ?? '';
 //$erp_code=$_POST['erp_code'] ?? '';


//$database->update("Tbl_Gce_Artigos", [ "strCodBarras" => $barcode], ["strCodigo" => $erp_code]);
 
$output = array("success"=>1,"message"=>"Csucesso","debug"=>$_POST);

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST' && $accao=="updateBarcode"){
 $barcode=$_POST['barcode'] ?? '';
 $erp_code=$_POST['erp_code'] ?? '';

$database->update("Tbl_Gce_Artigos", [ "strCodBarras" => $barcode], ["strCodigo" => $erp_code]);
 
$output = array("success"=>1,"message"=>"Código de barras atualizado com sucesso","debug"=>$_POST);

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST' && $accao=="webdav"){


   // die(json_encode($_POST));

function respond(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST, OPTIONS');
    respond(405, [
        'success' => false,
        'error' => 'Método não suportado. Utilize POST.'
    ]);
}

// Lê corpo JSON ou form-data

$rawBody = file_get_contents('php://input');
$data = null;
if ($rawBody !== false && trim($rawBody) !== '') {
    $decoded = json_decode($rawBody, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $data = $decoded;
    }
}
if ($data === null) {
    $data = $_POST;
}


$cloudDirectory=$data['cloudDirectory'] ?? '';
$serverRaw = $data['server'] ?? '';
$fileName = $data['fileName'] ?? $data['filename'] ?? '';
$mime = $data['mime'] ?? 'application/octet-stream';
$login = $data['login'] ?? '';
$password = $data['password'] ?? '';
$contentBase64 = $data['fileContentBase64'] ?? $data['fileContentBase64'] ?? '';
$contentRaw = $data['fileContent'] ?? '';


if (!is_string($serverRaw) || trim($serverRaw) === '') {
    respond(400, ['success' => false, 'error' => 'Servidor WebDAV não definido.']);
}
if (!is_string($fileName) || trim($fileName) === '') {
    respond(400, ['success' => false, 'error' => 'Nome do ficheiro não definido.']);
}

// Normaliza URL base (sem / no fim)
$server = rtrim(str_replace('\\', '/', trim($serverRaw)), '/');
if (!preg_match('~^https?://~i', $server)) {
    $server = 'https://' . $server;
}

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

// ✅ Garante que é o endpoint correto (ownCloud/Nextcloud)
if (!str_contains($server, '/remote.php/dav/files/')) { 
    if (!is_string($login) || $login === '') {
        respond(400, ['success' => false, 'error' => 'É necessário o campo "login" para formar o caminho DAV.']);
    }
    $server = rtrim($server, '/') . '/remote.php/dav/files/' . rawurlencode($login);
}



      
$fileName = ltrim($fileName, '/');

// Codifica caminho corretamente (cada parte)
$parts = array_map('rawurlencode', explode('/', $fileName));
$relativePath = implode('/', $parts);
$targetUrl = $server . '/' . $relativePath;

// Decodifica conteúdo
$fileContent = '';
if (is_string($contentBase64) && $contentBase64 !== '') {
    $decodedContent = base64_decode($contentBase64, true);
    if ($decodedContent === false) {
        respond(400, ['success' => false, 'error' => 'Conteúdo base64 inválido.']);
    }
    $fileContent = $decodedContent;
} elseif (is_string($contentRaw) && $contentRaw !== '') {
    $fileContent = $contentRaw;
} else {
    respond(400, ['success' => false, 'error' => 'Nenhum conteúdo enviado.']);
}
// Função auxiliar para criar pastas (MKCOL)
function create_dav_directory(string $baseUrl, string $dir, string $login, string $password): void {
    $accum = '';
    foreach (explode('/', trim($dir, '/')) as $part) {
        $accum .= ($accum ? '/' : '') . $part;
        $url = $baseUrl . '/' . implode('/', array_map('rawurlencode', explode('/', $accum))) . '/';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => 'MKCOL',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "$login:$password",
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}

// Cria diretórios, se houverem
$dir = dirname($fileName);
if ($dir !== '.' && $dir !== '/') {
    create_dav_directory($server, $dir, $login, $password);
}

// 🔧 Envio do ficheiro
$ch = curl_init($targetUrl);
if ($ch === false) {
    respond(500, ['success' => false, 'error' => 'Falha ao inicializar cURL.']);
}

$headers = [
    'Content-Type: ' . $mime,
    'Expect:' // evita "100-continue"
];

curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POSTFIELDS => $fileContent,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => "$login:$password",
]);

$response = curl_exec($ch);
if ($response === false) {
    $err = curl_error($ch);
    $code = curl_errno($ch);
    curl_close($ch);
    respond(502, [
        'success' => false,
        'error' => 'Erro ao contactar o servidor WebDAV.',
        'details' => $err,
        'code' => $code,
        'target' => $targetUrl
    ]);
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE) ?: 0;
$body = substr($response, $headerSize);
curl_close($ch);

// Resposta
if ($httpCode < 200 || $httpCode >= 300) {
    respond(502, [
        'success' => false,
        'error' => 'Servidor WebDAV devolveu um erro.',
        'status' => $httpCode,
        'response' => $body,
        'target' => $targetUrl
    ]);
}

$metadata = [
    'ficheiro'     => 'webdav',
    'url'          => $targetUrl,
    'recebido_em'  => date('c'),
]; 
file_put_contents('logs/picker.log', json_encode($metadata) . PHP_EOL, FILE_APPEND);

respond(200, [
    'success' => true,
    'status' => $httpCode,
    'message' => 'Ficheiro enviado com sucesso para o servidor WebDAV.',
    'target' => $targetUrl
]);


}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST' && $accao=="batch"){


$UPLOAD_DIR = __DIR__ . '/logs';


$originalName = $_POST['nomeficheiro'] ?? 'picker.txt';
$mimeType     = $_POST['ficheiro_tipo'] ?? 'text/plain';
$rowCount     = isset($_POST['linhas']) ? (int)$_POST['linhas'] : null;
$pickerType   = $_POST['tipo_interno'] ?? ($_POST['tipo'] ?? '');
$recipient    = $_POST['destinatario'] ?? '';

// Normaliza nome do ficheiro e pasta de destino
$cleanName = preg_replace('/[^a-z0-9._-]+/i', '_', $originalName);
if ($cleanName === '' || $cleanName === '.') {
    $cleanName = 'picker.txt';
}
if (!is_dir($UPLOAD_DIR) && !mkdir($UPLOAD_DIR, 0770, true) && !is_dir(UPLOAD_DIR)) {
    http_response_code(500);
    echo json_encode(['success' => 0, 'mensagem' => 'Falha ao preparar diretório de upload.']);
    exit;
}
$targetPath = $UPLOAD_DIR . '/' . $cleanName;

// Extrai conteúdo: ficheiro físico, Base64 ou texto
$content = null;
if (!empty($_FILES['ficheiro']['tmp_name']) && is_uploaded_file($_FILES['ficheiro']['tmp_name'])) {
    $content = file_get_contents($_FILES['ficheiro']['tmp_name']);
} elseif (!empty($_POST['ficheiro_base64'])) {
    $decoded = base64_decode((string)$_POST['ficheiro_base64'], true);
    if ($decoded === false) {
        http_response_code(400);
        echo json_encode(['success' => 0, 'mensagem' => 'Base64 inválido.']);
        exit;
    }
    $content = $decoded;
} elseif (array_key_exists('ficheiro_conteudo', $_POST)) {
    $content = (string)$_POST['ficheiro_conteudo'];
}

if ($content === null) {
    http_response_code(400);
    echo json_encode(['success' => 0, 'mensagem' => 'Nenhum conteúdo recebido.']);
    exit;
}

// Guarda o ficheiro
if (file_put_contents($targetPath, $content) === false) {
    http_response_code(500);
    echo json_encode(['success' => 0, 'mensagem' => 'Não foi possível gravar o ficheiro.']);
    exit;
}

// (Opcional) registar metadados ou enviar email
$metadata = [
    'ficheiro'     => $cleanName,
    'mime'         => $mimeType,
    'linhas'       => $rowCount,
    'tipo_picker'  => $pickerType,
    'destinatario' => $recipient,
    'recebido_em'  => date('c'),
];
file_put_contents($UPLOAD_DIR . '/picker.log', json_encode($metadata) . PHP_EOL, FILE_APPEND);



$mail = new PHPMailer(true);
try {
    $mail->isMail();
    /*$mail->isSMTP();
    $mail->Host = 'smtp.exemplo.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'utilizador@exemplo.com';
    $mail->Password = 'palavra_passe';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;*/

    $mail->setFrom('noreply@teknisoft.pt', 'Teknisof Picker');
    $mail->addAddress($recipient);

    $mail->isHTML(true);
    $mail->Subject = 'Envio de Ficheiro - Picker';
    $mail->Body    = 'Em anexo enviamos o <b>ficheiro</b> enviado ao picker.';
    $mail->AltBody = 'Em anexo enviamos o ficheiro enviado ao picker.';
    $mail->addAttachment($targetPath, $cleanName);

    $mail->send();
    
    $output = array("success"=>1,"debug"=>$_POST,"mensagem"=>"Registo adicionado","path"=>basename($targetPath));       

} catch (Exception $e) {
    echo json_encode(["success" => 0, "mensagem" => "Erro ao enviar: {$mail->ErrorInfo}"]);
    exit;
}


}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($output)){
    echo json_encode($output);
}

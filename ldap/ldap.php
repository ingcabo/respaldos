<?php

// Credenciales de prueba
$user = "_BackOfficeEasyCL";
$pass = "s0zvw+KK8I7yyQNlVlMXrA==";

// Datos de acceso al servidor LDAP
$host = "192.168.50.132";
$port = "389";

// Conexto donde se encuentran los usuarios
$basedn = "cn=GG_BackOfficeEasy_AR,ou=Appl Groups,ou=Central,ou=AR,dc=cencosud,dc=corp";
//cn=GG_BackOfficeEasy_AR,ou=Appl Groups,ou=Central,ou=AR,dc=cencosud,dc=corp
// Atributos a recuperar
$searchAttr = array("dn", "cn", "sn", "givenName");

// Atributo para incorporar en la respuesta
$displayAttr = "cn";

// Respuesta por defecto
$status = 1;
$msg = "";
$userDisplayName = "null";

// Recuperar datos del POST
if (isset($_POST['user'])) {
        $user = $_POST['user'];
}
if (isset($_POST['pass'])) {
        $pass = $_POST['pass'];
}

// Establecer la conexión con el servidor LDAP
$ad = ldap_connect("ldap://{$host}:{$port}") or die("No se pudo conectar al servidor LDAP.");

// Autenticar contra el servidor LDAP
ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);

if (@ldap_bind($ad, "uid={$user},{$basedn}", $pass)) {
        // En caso de éxito, recuperar los datos del usuario
        $result = ldap_search($ad, $basedn, "(uid={$user})", $searchAttr);
        $entries = ldap_get_entries($ad, $result);
		
        if ($entries["count"]>0) {
                // Si hay resultados en la búsqueda
                $status = 0;
                if (isset($entries[0][$displayAttr])) {
                        // Recuperar el atributo a incorporar en la respuesta
                        $userDisplayName = $entries[0][$displayAttr][0];
                        $msg = "Autenticado como {$userDisplayName}";
                }
                else {
                        // Si el atributo no está definido para el usuario
                        $userDisplayName = "-";
                        $msg = "Atributo no disponible ({$displayAttr})";
                }
        }
        else {
                // Si no hay resultados en la búsqueda, retornar error
                $msg = "Error desconocido";
        }
}
else {
        // Si falla la autenticación, retornar error
        $msg = "Usuario y/o contraseña inválidos";
}

// Respuesta en formato JSON
header('Content-Type: application/json');
echo  $basedn;

echo "{\"uid\": \"{$user}\", \"estado\": \"{$status}\", \"nombre\": \"{$userDisplayName}\", \"debug\": \"{$msg}\"}";
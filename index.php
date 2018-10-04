<?
require_once("stdenc.php");
require_once("dbaccess.php");
$enc = new encryption_class();
if(@$_POST['generate']){
	$num = $_POST['num'];
	$name = $_POST['name'];	
	$msg = $_POST['msg'];	
	if(!ctype_digit($num)){ $e = 'Number of Users invalid.';
	} elseif(strlen($name)<3){ $e = 'Client name too short.';
	} else {
		$info = $name.'~'.$num.'~'.$msg;
		$key = $enc->Key;
		$length = $enc->length;
		$licencecode = $enc->encrypt($key, $info, $length);
		$errors = $enc->errors;
		if(count($errors)>0){ 
			for($i=0;$i<count($errors);$i++){
			@$e .= '- '.$errors[$i].'<br>';
			}
		} else { 
			$db = new dbaccess();
			$sql = "insert into licences (clientname, noofusers, licence,message) values ('$name','$num','$licencecode','$msg')";
			$db->query($sql) or die(mysql_error());
			$e = 'Licence code generated.';
		}
	}
}
?>
<title>Licence Generator</title>
<?= '<span style="color:red; font-family:Arial"><b>'.@$e.'</b></span>' ?>
<br>
<form name="form1" method="post" action="index.php" style="font-family:Arial, Helvetica, sans-serif; font-size:11px">
  Client Name - 
  <input name="name" type="text" id="name" size="50" maxlength="255" />
   Number of Users - 
   <input name="num" type="text" id="num" size="20" maxlength="3" />
   Alert Message - 
   <input name="msg" type="text" id="num" size="50" maxlength="255" />
   <input type="submit" name="generate" id="generate" value="Get License Code">
</form>
<? if(strlen(@$licencecode)==40){ ?>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold; background-color:#D7D7D7; color:#000; border:solid #999 1px; padding: 10px; width:530px"><? if(strlen($licencecode)==40){ echo $licencecode; }?></div>
<? } ?>
<? 
$db = new dbaccess();
$a = "select * from licences";
$b = $db->query($a); 
while($c = $db->get_assoc($b)){ echo '<br>';
	echo $enc->decrypt('xVC490jK0pp',$c['licence']).'<br>'.$c['licence'].'<hr>';
}
?>
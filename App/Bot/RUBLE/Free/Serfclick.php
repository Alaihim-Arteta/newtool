<?php
const
register_link = "https://serfclick.net/i/141324",
host = "https://serfclick.net/",
youtube = "https://youtu.be/1AbiZi9XsFI";

//function Curl($u, $h = 0, $p = 0) {while(true){$ch = curl_init();curl_setopt($ch, CURLOPT_URL, $u);curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);curl_setopt($ch, CURLOPT_COOKIE,TRUE);if($p) {curl_setopt($ch, CURLOPT_POST, true);curl_setopt($ch, CURLOPT_POSTFIELDS, $p);}if($h) {curl_setopt($ch, CURLOPT_HTTPHEADER, $h);}curl_setopt($ch, CURLOPT_HEADER, true);$r = curl_exec($ch);$c = curl_getinfo($ch);if(!$c) return "Curl Error : ".curl_error($ch); else{$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));curl_close($ch);if(!$bd){print m."Check your Connection!";sleep(2);print "\r                    \r";continue;}return $bd;}}}
Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie");
ua();

print "Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
system("termux-open-url ".youtube);Ban(1);

//Bot-Core
function h($xml=0){
	if($xml){
		$h[] = "x-requested-with: XMLHttpRequest";
	}
	$h[] = "content-type:application/x-www-form-urlencoded; charset=UTF-8";
	$h[] = "cookie: ".Simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}

function dash(){
	$r = curl(host."user/his_in",h())[1];
	$user = explode('</span>',explode('<div class="list">Ваш логин <span class="list2">',$r)[1])[0];
	$bal = explode('</span>',explode('<span id="anim">',$r)[1])[0];
	return ["user"=>$user,"bal"=>$bal];
}
function Ocr($img){
	if(file_exists($img)){
		$ran = substr(str_shuffle("0123456789abcdef"), 0, 10);
		$apikey = $ran."88957";
		$hasil=json_decode(shell_exec('curl --silent -H "apikey:'.$apikey.'" --form "file=@'.$img.'" --form "language=eng" --form "ocrengine=2" --form "isOverlayRequired=false" --form "iscreatesearchablepdf=false" https://api.ocr.space/Parse/Image'))->ParsedResults[0]->ParsedText;
		$cap = str_split(preg_replace('/\s+/', '', $hasil));
		if($cap[1] == "+"){
			return $cap[0]+$cap[2];
		}
	}
	return 0;
}
ulang:
$r = dash();
if(!$r["user"]){
	print m."Cookie Expired!\n";
	hapus("Cookie");
	goto cookie;
}

print h."Username ".p."-> ".k.$r["user"].n;
print h."Balance  ".p."-> ".k.$r["bal"].n;
print line();

//Daily
$r = curl(host."user/bonus", h())[1];
if(preg_match("/До следующего бонуса осталось/",$r)){
	$data = "bonusday=Get+a+bonus";
	$r = curl(host."user/bonus", h(),$data);
	$ss = explode('",',explode('<script>swal("',$r)[1])[0];
	if($ss){
		print h."Sukses   ".p."-> ".k.$ss.n;
		print h."Balance  ".p."-> ".k.dash()["bal"].n;
		print line();
	}
}

print m."Surfing...\n";
print line();
while(true){
	$dat = [];
	$r = curl(host."user/surflink", h())[1];
	$id = explode("'",explode("showFrame(this, '",$r)[1])[0];
	if($id){
		$data = "visitserfstarts=".$id;
		curl(host."user/surflink",h(1),$data)[1];
		
		$r = curl(host."view/".$id,h())[1];
		
		$timer = explode(';',explode('stattime = ',$r)[1])[0];
		$cnt = explode("';",explode("var cnt = '",$r)[1])[0];
		
		Tmr($timer);
		
		$data = "num=0&cnt=".$cnt;
		$r = curl(host."ajax/surf/coin.php",h(),$data)[1];
		$id_Captcha = explode('"',explode('captcha.php?sid=',$r)[1])[0];//4239104
		preg_match_all('#<span class="serfnum" onclick="vernum((.*?));">(.*?)</span>#is',$r,$x);
		for($i=0;$i<count($x[2]);$i++){
			$dat[$x[3][$i]] = preg_replace("/[^0-9]/","", $x[2][$i])."\n";
		}
		
		//gambar
		$img = "captcha.png";
		$r = curl(host."assets/captcha/captcha-st/captcha.php?sid=".$id_Captcha,h())[1];
		file_put_contents($img,$r);
		$cap = Ocr($img);
		if(!$cap){
			continue;
		}
		unlink($img);
		$num = $dat[$cap];
		
		$data = "num=".$num."&cnt=".$cnt;
		$r = curl(host."ajax/surf/coin.php",h(),$data)[1];
		
		$res = explode(';',$r);
		if($res[0] == "OK"){
			print h."Sukses   ".p."-> ".k.$res[1].n;
			print h."Balance  ".p."-> ".k.dash()["bal"].n;
			print line();
		}
		continue;
		
	}
	print m."Surfing habis\n";
	print line();
	break;
}

print m."YouTube...\n";
print line();
while(true){
	$dat = [];
	$r = curl(host."user/vsurf", h())[1];
	$id = explode("'",explode("showFrame(this, '",$r)[1])[0];
	if($id){
		$r = curl(host."viewyt/".$id,array_merge(["Referer: https://serfclick.net/user/vsurf"],h()))[1];
		$cnt = explode("'",explode("cnt = '",$r)[1])[0];
		$tmr = explode(';',explode('var time=',$r)[1])[0];
		Tmr($tmr);
		
		$data = "num=0&cnt=".$cnt;
		$r = curl(host."ajax/surfv/coin.php",h(),$data)[1];
		$id_Captcha = explode('"',explode('captcha.php?sid=',$r)[1])[0];//4239104
		preg_match_all('#<span class="serfnum" onclick="vernum((.*?));">(.*?)</span>#is',$r,$x);
		for($i=0;$i<count($x[2]);$i++){
			$dat[$x[3][$i]] = preg_replace("/[^0-9]/","", $x[2][$i])."\n";
		}
		
		//gambar
		$img = "captcha.png";
		$r = curl(host."assets/captcha/captcha-st/captcha.php?sid=".$id_Captcha,h())[1];
		file_put_contents($img,$r);
		$cap = Ocr($img);
		if(!$cap){
			continue;
		}
		unlink($img);
		$num = $dat[$cap];
		
		$data = "num=".$num."&cnt=".$cnt;
		$r = curl(host."ajax/surfv/coin.php",h(),$data)[1];
		$res = explode(';',$r);
		if($res[0] == "OK"){
			print h."Sukses   ".p."-> ".k.$res[1].n;
			print h."Balance  ".p."-> ".k.dash()["bal"].n;
			print line();
		}
		continue;
	}
	print m."YouTube habis\n";
	print line();
	break;
}
tmr(3000);
$api = json_decode(file_get_contents("http://ip-api.com/json"),1);
$zone = $api["timezone"];
date_default_timezone_set($zone);
print h."Date".m.": ".p.date("d-M-Y H:i:s").n;
print line();
goto ulang;
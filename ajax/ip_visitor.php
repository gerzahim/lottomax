<?php 
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
   $ips = $_SERVER['HTTP_X_FORWARDED_FOR']; 
}  
elseif (isset($_SERVER['HTTP_VIA'])) { 
   $ips = $_SERVER['HTTP_VIA']; 
}  
elseif (isset($_SERVER['REMOTE_ADDR'])) { 
   $ips = $_SERVER['REMOTE_ADDR']; 
} 
else {  
   $ips = "unknown"; 
} 
echo $ips;
//    echo "Tu IP es: $ips"; 
?> 
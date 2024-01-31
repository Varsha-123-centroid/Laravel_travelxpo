<?php

namespace App\Helpers;

class CcAcripto
{
	public static function encrypt($plainText,$key)
{
	$key = self::hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	$encryptedText = bin2hex($openMode);
	return $encryptedText;
}

/*
* @param1 : Encrypted String
* @param2 : Working key provided by CCAvenue
* @return : Plain String
*/
public static  function decrypt($encryptedText,$key)
{
	$key = self::hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = self::hextobin($encryptedText);
	$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	return $decryptedText;
}
public static function customEncrypt($data, $encryptionKey)
{
    // Generate a random initialization vector (IV)
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    // Encrypt the data using AES-256-CBC encryption
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $encryptionKey, 0, $iv);

    // Combine the IV and encrypted data into a single string
    $result = base64_encode($iv . $encryptedData);

    return $result;
}
public static function customDecrypt($encryptedData, $encryptionKey)
{
    // Decode the base64-encoded data
    $data = base64_decode($encryptedData);

    // Extract the IV (first 16 bytes) and encrypted data
    $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
    $encryptedData = substr($data, openssl_cipher_iv_length('aes-256-cbc'));

    // Decrypt the data using AES-256-CBC decryption
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $encryptionKey, 0, $iv);
    return $decryptedData;
}
public static  function hextobin($hexString) 
 { 
	$length = strlen($hexString); 
	$binString="";   
	$count=0; 
	while($count<$length) 
	{       
	    $subString =substr($hexString,$count,2);           
	    $packedString = pack("H*",$subString); 
	    if ($count==0)
	    {
			$binString=$packedString;
	    } 
	    
	    else 
	    {
			$binString.=$packedString;
	    } 
	    
	    $count+=2; 
	} 
        return $binString; 
  } 
}
?>
<?php
class KoboldcouponSendData{
        
        private $data;
        
        public function __construct( $data )
        {
            $this->data = $data;
            
           
        }
        
        function encryptData($dataToEncrypt){
            
            $cipher = 'aes-256-ofb';
            
            if (in_array( $cipher , openssl_get_cipher_methods()))
            {
                $ivlen = openssl_cipher_iv_length($cipher);
                
                $iv = openssl_random_pseudo_bytes($ivlen);
                
                $data['data'] = openssl_encrypt($dataToEncrypt, 'aes-256-ofb', '~B-$mAf5~jm<Fz!p', $options=0, $iv);
                
                $data['iv'] = $iv;
                
                return $data;
                
            }
            
        }
        
        function encrytURLVarables( $array ){
            
            $data = encryptData( http_build_query( $array ) );
            
            return  http_build_query( $data );
            
        }
        
        public function sendDataCurl(){
            
            $data = self::encrytURLVarables($this->data);
            
            $ch = curl_init("https://couponsystem.koboldsoft.com/createpdf.php"); // cURL ínitialisieren
            
            curl_setopt( $ch, CURLOPT_HEADER, 0 ); // Header soll nicht in Ausgabe enthalten sein
            
            curl_setopt( $ch, CURLOPT_POST, 1 ); // POST-Request
            
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data ) ; // POST-Felder festlegen, die gesendet werden sollen
            
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            
            curl_exec( $ch ); // Ausführen
            
            curl_close( $ch ); // Object close
            
            
        }
        
}      
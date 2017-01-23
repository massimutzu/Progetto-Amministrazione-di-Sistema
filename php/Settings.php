<?php

/**
 * Classe che contiene una lista di variabili di configurazione
 *
 * @author
 */
class Settings {

    // variabili di accesso per il database
    public static $db_host = 'localhost';
    public static $db_user = 'lib';
    public static $db_password = 'l1bPwd!';
    public static $db_name='libreria';
    public static $db_port='3306';
    
    public static $inventarioPreCode='lib';
    public static $inventarioPadNums=6;   
    
    public static $MaxDayForRent=20;    
    
    private static $appPath;

    /**
     * Restituisce il path relativo nel server corrente dell'applicazione
     * Lo uso perche' la mia configurazione locale e' ovviamente diversa da quella 
     * pubblica. Gestisco il problema una volta per tutte in questo script
     */
    public static function getApplicationPath() {
        if (!isset(self::$appPath)) {
            // restituisce il server corrente
            switch ($_SERVER['HTTP_HOST']) {
                case 'localhost':
                    // configurazione pc locale
                   	 self::$appPath = 'http://' . $_SERVER['HTTP_HOST'] . '/';
                    	break;                
                default:
			self::$appPath = 'http://' . $_SERVER['HTTP_HOST'] . '/';                  
                    	break;
            }
        }        
        return self::$appPath;
    }
}

?>

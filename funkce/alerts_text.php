<?php

	/**
    * Soubor s funkcemi pro alerty
    */

	/**
	* Prevadi cislo chyby na text, ktery se vypise na obrazovku
	* @param $number cislo chyby
	* @return string text chyby
	*/
	function alert($number) {
		$err = "";
		switch($number) {
			case '-21':
				$err = "Komentář byl uložen.";
				break;
			case '-11':
				$err = "Chyba úspěšně uložena.";
				break;
			case '-2':
				$err = "Úspěšně jsme Vás odhlásili.";
				break;
			case '-1':
				$err = "Úspěšná registrace. Nyní se přihlaste.";
				break;
			case '1':
				$err = "Nezdařilo se přihlašení";
				break;
			case '2':
				$err = "Nemáte oprávněný přístup";
				break;
			case '3':
				$err = "Uživatel již existuje";
				break;
			case '4':
				$err = "Hesla se neshodují";
				break;
			case '5':
				$err = "Nevyplněna pole";
				break;
			case '6':
				$err = "Heslo má nevhodnou délku";
				break;
			case '7':
				$err = "Email má nevhodnou délku";
				break;

			case '11':
				$err = "Některé z polí je prázdné";
				break;
			case '12':
				$err = "Text se nepodařilo uložit do databáze";
				break;
			case '13':
				$err = "Jméno chyby je chybně vyplněno";
				break;
			case '13':
				$err = "Text chyby je chybně vyplněn";
				break;
			case '21':
				$err = "Komentář má nevhodnou délku";
				break;
			default:
				$err = "Chyba";
				break;
		}
		return $err;
	}
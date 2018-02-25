<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Nowicki
 * Date: 18.02.2018
 * Time: 21:40
 */

namespace KS\Plugin\User;


class AppConfig {

	/**
	 * @var string ID aplikacji, np. S00001
	 */
	public $id = '';

	/**
	 * @var string Nazwa aplikacji, np. "Moja super aplikacja!"
	 */
	public $nazwa = '';

	/**
	 * @var string Tajny klucz aplikacji, do wygenerowania w panelu
	 */
	public $klucz = '';

	/**
	 * @var string Adres zwrotny, gdzie aplikacja ma się przeładować po autoryzacji, np. http://app.sarmacja.org/
	 */
	public $adres_powrotu = '';

	/**
	 * @var bool Czy z poziomu aplikacji mają być dostępne przelewy?
	 */
	public $przelewy = true;

	/**
	 * @var int Limit pojedynczego przelewu, w libertach
	 */
	public $przelew_jednorazowy = 10;

	/**
	 * @var int Limit przelewów na dobę
	 */
	public $przelew_doba = 100;

	/**
	 * @var bool Czy używamy powiadomień?
	 */
	public $powiadomienia = TRUE;

	public function data() {
		return [
			'appId' => $this->id,
			'appName' => $this->nazwa,
			'appSecret' => $this->klucz,
			'adress' => $this->adres_powrotu,
			'options' => [
				'przelew' => $this->przelewy,
				'jednorazowyPrzelew' => $this->przelew_jednorazowy,
				'dniowyPrzelew' => $this->przelew_doba,
				'powiadomienie' => $this->powiadomienia,
			],
		];
	}

}

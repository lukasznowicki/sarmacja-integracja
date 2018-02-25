<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Nowicki
 * Date: 18.02.2018
 * Time: 20:17
 */

namespace KS\Plugin\User;

/**
 * Class Plugin
 *
 * @package KS\Plugin\User
 */
class Plugin {

	/**
	 * @var Odwołanie do klasy Integracja
	 */
	private $integracja;

	/**
	 * @var Dane użytkownika KS
	 */
	private $ks_user;

	/**
	 * @var Wynik wywołania integracji KS
	 */
	private $ks_wynik;

	/**
	 * Plugin constructor.
	 */
	function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Inicjalizacja WordPressa dobiegła końca
	 */
	function init() {
		$wp_user = wp_get_current_user();
		if ( 0 === $wp_user->ID ) {
			$this->require_authorization();
		}
	}

	/**
	 * @param $content Treść tego, co wyświetli się w linku
	 *
	 * @return string
	 */
	function auth_link( $content ) {
		return '<a href="' . $this->integracja->loginURL() . '">' . $content . '</a>';
	}

	/**
	 * @param $message
	 */
	function show_error( $message ) {
		wp_die( $message );
	}

	/**
	 * Autoryzacja właściwa
	 */
	function require_authorization() {
		$message = '';
		$this->integracja = new Integracja();
		$this->integracja->setConfiguration( ( new AppConfig() )->data() );
		$this->ks_user = $this->integracja->getUser();
		$this->ks_wynik = $this->integracja->getWynik();
		$error = (int)( isset( $this->ks_wynik['error'] ) ? $this->ks_wynik['error'] : -1 );
		if ( 666 === $error ) {
			$message = 'Dokonano zmiany w wymaganych uprawnieniach. Konieczna jest ' . $this->auth_link('ponowna autoryzacja') . ' w systemie Księstwa Sarmacji';
		} elseif ( 200 !== $error ) {
			$message = 'Wystąpił nieznany błąd. Konieczna jest ' . $this->auth_link('ponowna autoryzacja') . ' w systemie Księstwa Sarmacji';
		}
		if ( '' != $message ) {
			$this->show_error( $message );
		}
	}

}

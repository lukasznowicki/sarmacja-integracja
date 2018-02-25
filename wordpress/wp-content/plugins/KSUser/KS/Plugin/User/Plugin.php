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
	 * @param $user_id
	 */
	function login_user_by_id( $user_id ) {
		wp_clear_auth_cookie();
		wp_set_current_user ( $user_id );
		wp_set_auth_cookie  ( $user_id );
		wp_safe_redirect( home_url() );
		exit();
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
		if ( is_null( $this->ks_user ) || empty( $this->ks_user ) || !isset( $this->ks_user ) ) {
			$message = 'Aby przeglądać tę stronę musisz ' . $this->auth_link('autoryzować aplikację') . '.';
		}
		if ( '' != $message ) {
			$this->show_error( $message );
		}
		$user_query = new \WP_User_Query( array( 'meta_key' => 'KSI_paszport', 'meta_value' => $user['paszport'] ,  'fields' => 'all'  ) );
		$user_table = $user_query->get_results();
		if ( is_array( $user_table ) && ( 1 === count( $user_table ) ) ) {
			$wp_user = $user_table[0];
			$this->login_user_by_id( $wp_user->ID );
		}
		if ( isset( $this->ks_user['paszport'] ) && ( '' !== $this->ks_user['paszport'] ) ) {
			$new_user_password = wp_generate_password(32,TRUE);
			$new_user_email = ( isset( $this->ks_user['email'] ) && ( '' != $this->ks_user['email'] ) ) ? $this->ks_user['email'] : $this->ks_user['paszport'] . '@sarmacja.org';
			$new_user_id = wp_create_user( $this->ks_user['paszport'], $new_user_password, $new_user_email );
			wp_update_user([
				'ID' => $new_user_id,
				'nickname' => $this->ks_user['nick'] . ' [' . $this->ks_user['paszport'] . ']'
			]);
			add_user_meta( $new_user_id, 'KSI_paszport', $this->ks_user['paszport'], TRUE );
			add_user_meta( $new_user_id, 'KSI_data', $this->ks_user, TRUE );
			$ready_user = new \WP_User( $new_user_id );
			$ready_user->set_role('subscriber');
			$this->login_user_by_id( $new_user_id );
		}
		exit('Coś poszło bardzo nie tak.');
	}

}

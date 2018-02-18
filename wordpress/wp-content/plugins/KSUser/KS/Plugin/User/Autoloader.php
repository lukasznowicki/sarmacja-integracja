<?php

namespace KS\Plugin\User;

/**
 * Class Autoloader
 *
 * @package KS\Plugin\User
 */
class Autoloader {

	/**
	 * @var array
	 */
	protected $namespaces = [];

	/**
	 * @return bool
	 */
	public function register() {
		spl_autoload_register( [ $this, 'load_class' ] );

		return TRUE;
	}

	/**
	 * @param $prefix
	 * @param $dir
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function add_namespace( $prefix, $dir, $prepend = FALSE ) {
		$prefix = trim( $prefix, '\\' ) . '\\';
		$dir    = rtrim( $dir, DIRECTORY_SEPARATOR ) . '/';
		if ( isset( $this->namespaces[ $prefix ] ) === FALSE ) {
			$this->namespaces[ $prefix ] = [];
		}
		if ( $prepend ) {
			array_unshift( $this->namespaces[ $prefix ], $dir );
		} else {
			array_push( $this->namespaces[ $prefix ], $dir );
		}

		return TRUE;
	}

	/**
	 * @param $class
	 *
	 * @return bool|string
	 */
	public function load_class( $class ) {
		$prefix = $class;
		while ( FALSE !== $pos = strrpos( $prefix, '\\' ) ) {
			$prefix         = substr( $class, 0, $pos + 1 );
			$relative_class = substr( $class, $pos + 1 );
			$mapped_file    = $this->load_mapped_file( $prefix, $relative_class );
			if ( $mapped_file ) {
				return $mapped_file;
			}
			$prefix = rtrim( $prefix, '\\' );
		}

		return FALSE;
	}

	/**
	 * @param $prefix
	 * @param $relative_class
	 *
	 * @return bool|string
	 */
	protected function load_mapped_file( $prefix, $relative_class ) {
		if ( isset( $this->namespaces[ $prefix ] ) === FALSE ) {
			return FALSE;
		}
		foreach ( $this->namespaces[ $prefix ] as $base_dir ) {
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
			if ( $this->file_call( $file ) ) {
				return $file;
			}
		}

		return FALSE;
	}

	/**
	 * @param $file
	 *
	 * @return bool
	 */
	protected function file_call( $file ) {
		if ( is_readable( $file ) ) {
			require_once $file;

			return TRUE;
		}

		return FALSE;
	}

}
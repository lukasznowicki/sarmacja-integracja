<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Nowicki
 * Date: 25.02.2018
 * Time: 19:07
 */

namespace KS\Plugin\User;

/**
 * Class Profile
 *
 * @package KS\Plugin\User
 */
class Profile {

	/**
	 * Profile constructor.
	 */
	function __construct() {
		add_action( 'show_user_profile', [ $this, 'show_user_profile' ] );
	}

	/**
	 * @param $user
	 */
	function show_user_profile( $user ) {
		$meta = get_user_meta( $user->ID, 'KSI_data', TRUE );
		?>
		<h2>Dane profilowe Księstwa Sarmacji</h2>
		<table class="form-table">
			<tbody>
			<tr class="user-ks-paszport">
				<th>Numer paszportu</th>
				<td><?php echo $meta['paszport']; ?></td>
			</tr>
			<tr class="user-ks-nick">
				<th>Nick</th>
				<td><?php echo $meta['nick']; ?></td>
			</tr>
			<tr class="user-ks-bank">
				<th>Na koncie:</th>
				<td><?php echo number_format( $meta['money'], 2, ',', ' ' ); ?>
					lt
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

}
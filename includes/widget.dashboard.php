<?php
		GLOBAL $wpdb, $SULlyUtils;

		// Update any failed installs in the database.
		SULlyUpdateFails();

		// Update any WordPress updates that have happened with details.
		SULlyUpdateCores();

		// Update the database for any updates that have happened to ourselves.
		SULlyUpdateMyself();

		// Check for any changes to the system
		SULlyUpdateSystemSettings( SULlyGetSystemInfo(), unserialize( get_option( 'SULly_System_Settings' ) ) );

		$TableName = $wpdb->prefix . "SULly";
		$NumToDisplay = $SULlyUtils->get_option( 'EntriesToDisplay' );

		if( $NumToDisplay < 1 ) { $NumToDisplay = 10; }

		$Rows = $wpdb->get_results( "SELECT * FROM $TableName ORDER BY time desc LIMIT " . $NumToDisplay );

		echo "<div>";
		foreach( $Rows as $CurRow )
			{
			echo "<div>";

			echo "<div style='clear: both; float: left; font-size: 14pt'>";
			echo "<a href='" . esc_attr( $CurRow->itemurl ) . "' target=_blank>" . wp_kses_post( $CurRow->nicename ) . "</a>";
			echo "</div>";

			echo "<div style='float: right; font-size: 14pt'>";
			echo wp_kses_post( $CurRow->version );
			echo "</div>";

			echo "<div style='clear: both; float: left;'>";
			$phptime = strtotime( $CurRow->time );
			echo date( get_option('time_format'), $phptime ) . "&nbsp;" . date( get_option('date_format'), $phptime );
			echo "</div>";

			$TypeDesc = "Unknown";
			if( $CurRow->type == 'C' ) { $TypeDesc = __( 'WordPress Core', 'sully' ); }
			if( $CurRow->type == 'T' ) { $TypeDesc = __( 'Theme', 'sully' ); }
			if( $CurRow->type == 'P' ) { $TypeDesc = __( 'Plugin', 'sully' ); }
			if( $CurRow->type == 'S' ) { $TypeDesc = __( 'System', 'sully' ); }

			echo "<div style='float: right;'>";
			echo $TypeDesc;
			echo "</div>";

			echo "<div style='clear: both;'><br></div>";

			echo "<div style='clear: both; float: left;'>";
			if( $CurRow->type != '' )
				{
				echo preg_replace( '/\n/', '<br>', wp_kses_post( $CurRow->changelog ) );
				}
			else
				{
				echo wp_kses_post( $CurRow->filename );
				}

			echo "</div>";

			echo "<div style='clear: both;'><br></div>";

			echo "</div>";

			}

		echo "<div style='clear: both;'></div>";
		echo "<div style='float: right;'><a class=button-primary href='index.php?page=SULlyDashboard'>" . __( 'SULly Dashboard', 'sully' ) . "</a></div>";
		echo "<div style='clear: both;'></div>";
		echo "</div>";
?>
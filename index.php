<!DOCTYPE html>

<?php require('config.php'); ?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>localhost</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="<?= $projroot ?>css/main.css">
    </head>

    <body>

	    <div class="canvas">

		    <header>

			    <h1>My Local Sites</h1>

			    <nav>
			        <ul>
					<?php
			            foreach ( $devtools as $tool ) {
			            	printf( '<li><a href="%1$s" target="_blank">%2$s</a></li>', $tool['url'], $tool['name'] );
			            }
					?>
			        </ul>
			    </nav>

		    </header>

		    <div class="cf">
			<?php
				foreach ( $dir as $d ) {
					$dirsplit = explode('/', $d);
					$dirname = $dirsplit[count($dirsplit)-2];
					
					printf( '<ul class="sites %1$s">', $dirname );

					foreach( glob( $d ) as $file )  {

						$project = basename($file);

						if ( in_array( $project, $hiddensites ) ) continue;

						echo '<li>';

						$siteroot = sprintf( 'http://%1$s', $project, $dirname, $tld );

						// Display an icon for the site
						$icon_output = '<span class="no-img"></span>';
						foreach( $icons as $icon ) {

							if ( file_exists( $file . '/' . $icon ) ) {
								$icon_output = sprintf( '<img src="%1$s/%2$s">', $siteroot, $icon );
								break;
							} // if ( file_exists( $file . '/' . $icon ) )

						} // foreach( $icons as $icon )
						echo $icon_output;

						// Display a link to the site
						$displayname = $project;
						if ( array_key_exists( $project, $siteoptions ) ) {
							if ( is_array( $siteoptions[$project] ) )
								$displayname = array_key_exists( 'displayname', $siteoptions[$project] ) ? $siteoptions[$project]['displayname'] : $project;
							else
								$displayname = $siteoptions[$project];
						}
						printf( '<a class="site" href="%1$s" target="_blank">%2$s</a>', $siteroot, $displayname );


						// Display an icon with a link to the admin area
						$adminurl = '';
						// We'll start by checking if the site looks like it's a WordPress site
						if ( is_dir( $file . '/wp-admin' ) )
							$adminurl = sprintf( 'http://%1$s/wp-admin', $siteroot );

						// If the user has defined an adminurl for the project we'll use that instead
						if (isset($siteoptions[$project]) &&  is_array( $siteoptions[$project] ) && array_key_exists( 'adminurl', $siteoptions[$project] ) )
							$adminurl = $siteoptions[$project]['adminurl'];

						// If there's an admin url then we'll show it - the icon will depend on whether it looks like WP or not
						if ( ! empty( $adminurl ) )
							printf( '<a class="%2$s icon" href="%1$s" target="_blank">Admin</a>', $adminurl, is_dir( $file . '/wp-admin' ) ? 'wp' : 'admin' );


						echo '</li>';

					} // foreach( glob( $d ) as $file )

					echo '</ul>';

				} // foreach ( $dir as $d )
			?>
			</div>

			<div class="cf">
				<h3>Local Setup Documentation</h3>
				<p>Local DocumentRoot: <code>/Users/username/Sites</code><br>
				   Local project root: <code>/Users/username/Repositories</code></p>
				
				<h3>Add VirtualHost</h3>
				<p>Add VirtualHost configuration in <code>/usr/local/etc/httpd/extra/httpd-vhosts.conf</code>:</p>
				<xmp>
					<VirtualHost *:80>
						DocumentRoot "/Users/username/Sites/sitename.test"
						ServerName flamingoboyz.test
						<Directory "/Users/username/Sites/sitename.test">
							Options Indexes FollowSymLinks
							AllowOverride All
							Require all granted
						</Directory>
					</VirtualHost>
				</xmp>

				<p>Symlink project from local repository to <code>Sites</code> directory:</p>
				<code>ln -s /Users/username/Repositories/sitename/public /Users/username/Sites/sitename.test</code>

				<p>Restart apache:</p>
				<code>sudo apachectl -k restart</code>
			</div>

		    <footer class="cf">
		    <p></p>
		    </footer>

	    </div>
    </body>
</html>

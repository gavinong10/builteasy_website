<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_render_about(){

    ?>
    <div class="framed boxsizing">
        <div class="boxheader large">
            <span class="left about"><?php _e('About Google Pagespeed Insights for Wordpress', 'gpagespeedi'); ?></span>
        </div>
        <div class="padded">

            <p><?php _e('Google Pagespeed Insights is a tool that empowers you to make decisions that increase the performance of your website by expanding on the existing technology of Google Pagespeeds recommendations for current industry best practices for desktop and mobile web performance.', 'gpagespeedi'); ?></p>
            <p><?php _e('Through the addition of advanced data visualization, tagging, filtering, and snapshot technology, Google Pagespeed Insights for WordPress provides a comprehensive solution for any webmaster looking to increase their site performance, search engine ranking, and visitors browsing experience.', 'gpagespeedi'); ?></p>

			<h2 dir="ltr">
				<?php _e('Compare Versions', 'gpagespeedi'); ?>
			</h2>
			<table class="tblGenFixed" dir="ltr" id="tblMain" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr class="head" dir="ltr">
						<th class="s0 col1"></th>
						<th class="s1 col2" dir="ltr"><?php _e('Free Edition', 'gpagespeedi'); ?></th>
						<th class="s1 col3" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Premium Edition', 'gpagespeedi'); ?></a></th>
						<th class="s1 col4" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Agency Edition', 'gpagespeedi'); ?></a></th>
					</tr>
				</thead>
				<tbody>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Detailed Page Reporting', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Report Summaries', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Report Snapshots', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr"></td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Snapshot Comparison Tool', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr"></td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Add/Import Custom URLs', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr"></td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Desktop and Mobile Page Reports', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><a href="http://mattkeys.me/products/google-pagespeed-insights/" target="_blank"><?php _e('Scheduled Report Checks', 'gpagespeedi'); ?></a></td>
						<td class="s3 col2" dir="ltr"></td>
						<td class="s3 col3" dir="ltr">
							<span class="checkmark">X</span>
						</td>
						<td class="s3 col4" dir="ltr">
							<span class="checkmark">X</span>
						</td>
					</tr>
					<tr dir="ltr">
						<td class="s2 col1" dir="ltr"><?php _e('Site Licenses', 'gpagespeedi'); ?></td>
						<td class="s3 col2" dir="ltr"><?php _e('N/A', 'gpagespeedi'); ?></td>
						<td class="s3 col3" dir="ltr"><?php _e('1', 'gpagespeedi'); ?></td>
						<td class="s3 col4" dir="ltr"><?php _e('Unlimited**', 'gpagespeedi'); ?></td>
					</tr>
					<tr class="support" dir="ltr">
						<td class="s2 col1" dir="ltr"><?php _e('Support', 'gpagespeedi'); ?></td>
						<td class="s3 col2" dir="ltr"><?php _e('N/A', 'gpagespeedi'); ?></td>
						<td class="s3 col3" dir="ltr"><?php _e('Forum Based', 'gpagespeedi'); ?></td>
						<td class="s3 col4" dir="ltr"><?php _e('Email &amp; Forum Based', 'gpagespeedi'); ?></td>
					</tr>
					<tr class="price" dir="ltr">
						<td class="s2 col1" dir="ltr"><?php _e('Price', 'gpagespeedi'); ?></td>
						<td class="s3 col2" dir="ltr">
							<?php _e('Installed', 'gpagespeedi'); ?>
						</td>
						<td class="s5 col3">
							$29.99
						</td>
						<td class="s5 col4">
							$189.99
						</td>
					</tr>
				</tbody>
			</table>
			<p><?php _e('** Use Google Pagespeed Insights for Wordpress on any site that you own or manage.', 'gpagespeedi'); ?></p>
            <br />
        </div>
    </div>
    <?php
}


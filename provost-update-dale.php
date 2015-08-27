<?php
/*
WP Post Template: Dale Whittaker
*/
the_post();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Provost's Update - <?=the_title();?></title>
<style type="text/css">
	<!--
	html, body { margin:0; padding:0; background-color:#FFF; color:#333; }
	-->
	/* CSS Resets */
	.ReadMsgBody { width: 100%; background-color: #ffffff;}
	.ExternalClass {width: 100%; background-color: #ffffff;}
	.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
	body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
	body {margin:0; padding:0;}
	table {border-spacing:0;}
	table td {border-collapse:collapse;}

	* {zoom:1;}
	a {color:#006699;}
	div, p, a, li, td { -webkit-text-size-adjust:none; } /* ios likes to enforce a minimum font size of 13px; kill it with this */

	@media all and (max-width: 640px) {
		/* The outermost wrapper table */
		table[class="t640"] {
			width: 100% !important;
			border-left: 0px solid transparent !important;
			border-right: 0px solid transparent !important;
			margin: 0 !important;
		}

		/* The firstmost inner tables, which should be padded at mobile sizes */
		table[class="t524"] {
			width: 100% !important;
			padding-left: 15px;
			padding-right: 15px;
			padding-top: 15px !important;
			border-left: 0px solid transparent !important;
			border-right: 0px solid transparent !important;
			margin: 0 !important;
		}

		/* Generic class for a table column that should collapse to 100% width at mobile sizes (with bottom padding) */
		td[class="ccollapse100pb"] {
			display: block !important;
			overflow: hidden;
			width: 100% !important;
			float: left;
			clear: both;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 0 !important;
			padding-right: 0 !important;
			padding-bottom: 20px !important;
			border-left: 0px solid transparent !important;
			border-right: 0px solid transparent !important;
		}

		/* Generic class for a table column that should collapse to 100% width at mobile sizes (with top padding) */
		td[class="ccollapse100pt"] {
			display: block !important;
			overflow: hidden;
			width: 100% !important;
			float: left;
			clear: both;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 0 !important;
			padding-right: 0 !important;
			padding-top: 20px !important;
			border-left: 0px solid transparent !important;
			border-right: 0px solid transparent !important;
		}

		/* Generic class for a table column that should collapse to 100% width at mobile sizes */
		td[class="ccollapse100"] {
			display: block !important;
			overflow: hidden;
			clear: both;
			width: 100% !important;
			float: left !important;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 0 !important;
			padding-right: 0 !important;
			border-left: 0px solid transparent !important;
			border-right: 0px solid transparent !important;
		}

		/* Generic class for a table within a column that should be forced to 100% width at mobile sizes */
		table[class="tcollapse100"] {
			width: 100% !important;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 0 !important;
			padding-right: 0 !important;
			border-left: 0px solid transparent !important;
			border-right: 0px solid transparent !important;
		}

		/* Forces an image to fit 100% width of its parent */
		img[class="responsiveimg"] {
			width: 100% !important;
		}

		/* remove padding since 100% width */
		img[class="responsiveimgpb"] {
			width: 100% !important;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 0 !important;
			padding-right: 0 !important;
			padding-bottom: 20px !important;
		}
	}
</style>
</head>
<body>
<table class="t640" width="640">
	<tr>
		<td><img src="<?=THEME_IMG_URL?>/dale/banner.png" alt="UCF banner" class="responsiveimg" /></td>
	</tr>
	<tr>
		<td>
			<table class="t524" width="524" align="center">
				<tr>
					<td style="padding-bottom: 20px;">
						<table class="tcollapse100" width="524" border="0" align="center">
							<tr>
								<td class="ccollapse100pb" style="font-family: Georgia, serif; padding-bottom: 26px;font-size: 52px; font-weight: normal; line-height: 52px;">
									<?=the_title();?>
								</td>
							</tr>
							<tr>
								<td class="ccollapse100pb" style="padding-bottom: 12px;">
									<table class="tcollapse100" align="right">
										<?php if (has_post_thumbnail()) : ?>
										<tr>
											<td class="ccollapse100" style="padding-bottom: 6px;">
												<?php
													$featured_image_attr = array(
														'class'  => 'responsiveimg',
														'align'  => 'right',
														'style'  => 'padding-left: 3px;'
													);
													the_post_thumbnail('full', $featured_image_attr);
												?>
											</td>
										</tr>
										<tr>
											<td class="ccollapse100pb" style="font-size: 11px; padding-left: 3px; padding-bottom: 20px;">
												<?php
													$featured_image_id = get_post_thumbnail_id();
													$featured_image = get_post($featured_image_id);
													echo $featured_image->post_content;
												?>
											</td>
										</tr>
										<?php
											endif;
											// Make email ready
											$tmp_content = get_the_content();

											/*
												Use the WP's p additions to determine where to put TR & TD
												source: http://codex.wordpress.org/Function_Reference/wpautop
											*/
											$tmp_content = apply_filters('the_content', $tmp_content);
											echo str_replace('</p>', '</td></tr>', str_replace('<p>', '<tr><td class="ccollapse100" style="font-family:Georgia, serif; font-size: 15px; line-height: 24px; padding-bottom: 12px">', $tmp_content));
										?>
										<tr>
											<td class="ccollapsepb100" style="padding-bottom: 30px; font-family:Georgia, serif; font-size: 15px; line-height: 24px;">
												All the best,
											</td>
										</tr>
										<tr>
											<td class="ccollapse100pb" style="padding-bottom: 10px;">
												<img src="<?=THEME_IMG_URL?>/dale/profile-circle.jpg" alt="Provost Dale Whittaker, Ph.D" align="right" />
												<img src="<?=THEME_IMG_URL?>/dale/signature.gif" alt="Provost Dale Whittaker, Ph.D" align="left" />
											</td>
										</tr>
										<tr>
											<td class="ccollapse100pb" style="padding-bottom: 40px;">
												<span style="font-weight: bold;">A. Dale Whittaker, Ph.D.</span><br>
												Provost and Executive Vice President<br>
												Professor, Department of Civil, Environmental, and Construction Engineering
											</td>
										</tr>
										<tr>
											<td class="ccollapse100">
												<table style="font-size: 13px;">
													<tr>
														<td style="padding-bottom: 5px;">
															University of Central Florida • 4000 Central Florida Blvd.
														</td>
													</tr>
													<tr>
														<td style="padding-bottom: 5px;">
															Orlando, FL 32816-0065 • <a style="color: #006699;" href="http://www.provost.ucf.edu">http://www.provost.ucf.edu</a>
														</td>
													</tr>
													<tr>
														<td style="padding-bottom: 5px;">
															<unsubscribe>Click here to unsubscribe.</unsubscribe>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>

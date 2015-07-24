<?php
/*
WP Post Template: Tony Waldrop
Description: This HTML is really ugly because it's a modified form of the email template. Requires WP Post Template plugin.
*/
the_post();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Provost's Update</title>
<style type="text/css">
<!--
html, body { margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px;
background-color:#dbdcdc; color:#3f3d3d; font-family:Georgia, "Times New Roman", Times, serif;
font-size:14px; padding:0; }
#left { font-size:14px; line-height:21px; }
#left p { font-size:14px;  line-height:21px;}
#right { font-family:'Lucida Grande', Arial, sans-serif; font-size:10px; padding:0 0 0 20px;}
#box { padding:16px 10px 10px 26px; }
#box p { margin:0; padding:0 0 5px 0; }
#box ul { padding:0; margin:0; font-family:"Lucida Grande", Arial, sans-serif; font-size:10px; }
#box li { list-style:none; color:#9c0404; margin:0; padding:0; line-height:17px; }
#box li a { color:#9c0404; text-decoration:underline;  }
#foot a { color:#a1a1a1; text-decoration:underline; }
h2 { text-align:center; color:#666; font-family:Georgia, "Times New Roman", Times, serif; font-size:15px; margin-bottom:6px; padding:0; }
a { color:#660000; text-decoration: none;}
a:hover { color:#E50606; }
a img { border: 0; }
img { border:0; }
table, td, tr { border-collapse:collapse; margin:0; padding:0; vertical-align:top; }
#title { position:relative; height:65px; background:url('<?php echo THEME_IMG_URL; ?>/update-title.jpg') no-repeat top left; }
#title h1 { display:none; }
#title .date { float:right; padding:30px 50px 10px; font-family:georgia, serif; color:black; font-size:13px; }

-->
</style>
</head>
<body>
<div id="main">
	<div style="padding: 0;">
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="width: 600px; margin:0 auto; background-color: #fff;">
		<tr><td height="37" style="border-bottom: 2px solid #FFC904; background-color: #000; padding-left: 20px;"><a href="http://www.ucf.edu/"><img alt="University of Central Florida" src="<?php echo THEME_IMG_URL; ?>/update-ucf.gif"></a></td></tr>
		<tr><td>
			<table border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
				<tr>
					<td colspan="2" style="background-color: #fffae4;" id="title">
						<h1>Provost's Update</h1>
						<div class="date"><?php echo date( 'l, F j, Y', strtotime( $post->post_title ) ); ?></div>
					</td>
				</tr>
				<tr><td colspan="2">
					<div style="border-bottom: 1px solid #aaa; margin: 0 20px 0 20px; width: 560px; height: 1px;">&nbsp;</div>
				</td></tr>
				<tr><td id="left" style="padding: 24px 20px 0 20px;" width="auto">
					<?php the_content(); ?>
					<p>Sincerely,<br/><br/>Tony Waldrop</p>
				</td>
				<td id="right" style="padding: 0 10px;">
					<div style="padding-top:24px; padding-bottom:15px; line-height: 21px;"><img style="width: auto;" src="<?php echo THEME_IMG_URL; ?>/tony-waldrop-photo2.jpg" alt="Tony G. Waldrop, Ph.D. Provost and Vice President for Academic Affairs"></div>
					<h2>Contact the Provost</h2>
					<a href="mailto:provostcomments@ucf.edu"><img src="<?php echo THEME_IMG_URL; ?>/update-feedback.gif"></a></div>

				</td></tr>
				<tr><td style="padding: 0 20px;" colspan="2">
					<div style="font-family:'Lucida Grande', Arial, sans-serif; font-size:11px; margin:0 0 20px 0; border-top:1px solid #ededed;color:#a1a1a1;padding-top:10px;line-height:150%;" id="foot">
						University of Central Florida &bull; 4000 Central Florida Blvd. &bull; Orlando, FL 32816-0065<br>
						<a href="http://www.provost.ucf.edu" style="color:#a1a1a1;text-decoration:underline;">http://www.provost.ucf.edu</a> &nbsp;&nbsp;|&nbsp;&nbsp;<unsubscribe>Click here to unsubscribe.</unsubscribe>
					</div>
				</td></tr>
			</table>
		</td></tr>
	</table>
	</div>
</div>
</body>
</html>

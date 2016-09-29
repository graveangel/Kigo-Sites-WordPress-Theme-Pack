<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Template Name: Property Detail Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kigo-blank
 */

get_header(); 

$data = get_post_meta(get_the_ID(), 'bapi_property_data', true);

if(isset($_GET['debug']) && $_GET['debug'] == 'raw') { echo "Raw data:<pre>"; print_r($data); echo "</pre>"; }

$data = json_decode($data);
$context = $data->ContextData;

if(isset($_GET['debug'])) { echo "Decoded data:<pre>"; print_r($data); echo "</pre>"; }

$translations = getbapitextdata();

global $bapi_all_options; 
//$settings = json_decode($bapi_all_options['bapi_sitesettings']);
$settings = get_option('bapi_sitesettings_raw');

if(isset($_GET['debug'])) { echo "Debug data ...<pre>"; print_r($settings); echo "</pre>"; }

if($_GET['debug'] == 'session') { echo "Session stuff ...<pre>"; print_r($_SESSION); echo "</pre>"; }
?>

<article class="span9">

<?php
if($data) {  
?>

	<div class="bapi-entityadvisor" data-pkid="<?php echo $data->ID; ?>" data-entity="property"></div>
		<section class="row-fluid">
		<div class="span12 item-snapshot module shadow-border">        
			<div class="top-block">
			<div class="row-fluid">
				<div class="span7 left-side">
				<h2 class="title"><?php echo $data->Headline; ?></h2>
				<p class="location"><span><b><?php echo $translations['City']; ?>:</b> <?php echo $data->City; ?></span> <?php if($data->Bedrooms) { ?><b><?php echo $translations['Beds']; ?></b>: <?php echo $data->Bedrooms; ?> | <?php } ?><?php if($data->Bathrooms) { ?><b><?php echo $translations['Baths']; ?></b>: <?php echo $data->Bathrooms; ?> | <?php } ?><b><?php if($data->Sleeps) { ?><?php echo $translations['Sleeps']; ?></b>: <?php echo $data->Sleeps; ?><?php } ?>		
				<?php if($settings['averagestarsreviews'] == 'on') { ?>		
						<?php if($data->NumReviews > 0) { ?>
						 		<div class="starsreviews"><div id="propstar-<?php echo $data->AvgReview; ?>"><span class="stars"></span><i class="starsvalue"></i></div></div>	
						<?php } ?>
				<?php } ?>
				</p>                
				</div>	
				<div class="span5 nav-links">
					<a class="link" href="/rentals/rentalsearch"><span>&larr;</span>&nbsp;<?php echo $translations['Back to Results']; ?></a>
				</div>
				</div>  
			</div>		
			<div class="item-slideshow">
				<div id="slider" class="flexslider bapi-flexslider loadimg" data-options='{ "animation": "slide", "controlNav": false, "animationLoop": false, "slideshow": false, "sync": "#carousel" }'>
					<ul class="slides">
					<?php $imgCount = 1; foreach($data->Images as $img) { ?>
						<li>
							<div>
								<img alt="<?php echo $img->Caption; ?>" title="Open Slideshow" <?php if($imgCount > 3) { echo 'src="data:image/png;base64,R0lGODlhMANYAuMJAPDw8ENDQ3V1dZWVldfX16+vr+vr61VVVcbGxv///////////////////////////yH/C05FVFNDQVBFMi4wAwEAAAAh/ggoYSkgS0lHTwAh+QQACgD/ACwAAAAAMANYAgAE/hDISau9OOvNu/9gKI5kaZ5oqq5s675wLM90bd94ru987//AoHBILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/4LB4TC6bz+i0es1uu9/wuHxOr9vv+Lx+z+/7/4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NHS09TV1tfY2drb3N3e3+Dh4uPk5ebn6Onq6+zt7u/w8fLz9PX29/j5+vv8/f7/AAMKHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8eP/iBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1izat3KtavXr2DDih1LtqzZs2jTql3Ltq3bt3Djyp1Lt67du3jz6t3Lt6/fv4ADCx5MuLDhw4gTK17MuLHjx5AjS55MubLly5gza97MubPnz6BDix5NurTp06hTq17NurXr17Bjy55Nu7bt27hz697Nu7fv38CDCx9OvLjx48iTK1/OvLnz59CjS59Ovbr169iza9/Ovbv37+DDix9Pvrz58+jTq1/Pvr379/Djy59Pv779+/jz69/Pv7///wAG/ijggAQWaOCBCCao4IIMNujggxBGKOGEFFZo4YUYZqjhhhx26OGHIIYo4ogklmjiiSimqOKKLLbo4oswxijjjDTWaOONOOao44489ujjj0AGKeSQRBZp5JFIJqnkkkw26eSTUEYp5ZRUVmnllVhmqeWWXHbp5ZdghinmmGSWaeaZaKap5ppstunmm3DGKeecdNZp55145qnnnnz26eefgAYq6KCEFmrooYgmquiijDbq6KOQRirppJRWaumlmGaq6aacdurpp6CGKuqopJYiwAGlgkLAAQVYcACqFRRwgAGpWrIqrBS86uoBBNRaiQGv9porrhLcSquvjAwwgAWn/rY6bAUDHCCABQQci6wgwB6AQKzSVqAr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KOOMNNZo44045qjjjjz26OOPQAYp5JBEFmnkkUgmqeSSTDbp5JNQRinllFRWaeWVWGap5ZZcdunll2CGKeaYZJZp5plopqnmmmy26eabcMYp55x01mnnnXjmqeeefPbp55+ABirooIQWauihiCaq6KKMNuroo5BGKumklFZq6aWYZqrpppx26umnoIYq6qikFgLAqaimquqqrKYqgACtxiqrqqWmMeutBAhQwKoHHLBqAQIYcOustaIxrKwEHACrqr3+8noAAcfGWuwZ0bZqgADPMutrqskeIGy1q05rRrUD7KrqALpqq2oBBwyw6gDuRituGdFeKwAC67arbqoDHGAuqgg0K++8Y1QLbLCpGqDst6cKfKrC2aIK8b/DElxwva9SDAC20KLqMADJLosquwgPbDEY4AZc8qnsUoxuquzG+3Cv+FZ7chixElBAx/wKIDPIAvDMKgJB86vsqgYMUDOrN6Pc6sEMn5pr0eBy26vQAED8M61NexGrvQNg3XLVMOubcK9UM911F7Lau3LWCERdtQFxc4utt7KuzXbb6L5N9qwQ45233lvcmvSrcv/NKsR+q014FsMevrTiraqMdav+j2sBs66JG3A55avWraoBMaeaOeQjv6o06KwDEHCvP5+OhaoE9B1262Tn2usBkwMg+xVDv8o57scmvfsAif9uhbUH907807AnfqryVSAr+vNtF+C86dRPgf33jncPBcifg085AtCKLwUA6MKLvvl/63y3r+pH4Xrfqu8M/7BE7757+vV7gsQQUADb7e9W/iMAARgWQAGuigDvO2DbDCC9BjpBgt+zYBMwiD0NMoGDz/PgEkBIPBEqoX0+S+HWSIgqBbrwhSZMArxU6LMVshBk6MshBAkQQyTckHU9PMIPQRdEIwyRckUswhEVl0QisGqHS/RcBZsohAEWEF42BOH+DtFHQVRRsYoIwGIN9XdEzylQhwD84g/YBy/tSa+MZnyfGoGQtfItEWnCmuMa71g1PfqgbdfDoBQH58cdMA9edgQf3biIuUIacnRhdN8bzec5BKBvgeFypA5od8UCTPKAlWQk9zSJA5i1MZEcDKXQSJmDVIUxkA9DJei6ODoXjpKVNYhcAWX5t0V+0ou4tIHhCuhJSloSluELpgwAR8xfzhKCyOSaMmeQvXJFbZCKw+bDjvnJadLga81UFQG3Fy0oJoyb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ufPn0KNLn069uvXr2LNr3869u/fv4MOLH0++vPnz6NOrX8++vfv38OPLn0+/vv37+PPr38+/v///AAb+KOCABBZo4IEIJqjgggw26OCDEEYo4YQUVmjhhRhmqOGGHHbo4YcghijiiCSWaOKJKKao4oostujiizDGKOOMNNZo44045qjjjjz26OOPQAYp5JBEFmnkkUgmqeSSTDbp5JNQRinllFRWaeWVWGap5ZZcdunll2CGKeaYZJZp5plopqnmmmy26eabcMYp55x01mnnnXjmqeeefPbp55+ABirooIQWauihiCaq6KKMNuroo5BGKumklFZq6aWYZqrpppx26umnoIYq6qikFgLAqaimquqqrKZaQAGtxiqrqqWmMeutBhRAwKqvropAAQbcOmutaAgra66wqtqrsgP+BGtsq8Se8WyryDqLagEDqGrAAMBOy2q0ZkxLAAK+6qpssqgSMAC5qiLArrHglvGsAb9aeyoBBbx76rKoIjDArqmqm+2z8ZIhbr72Vpsqt6nm2mzD2Op7a8FjTEuvua52ey26AGzLMQD+agwvxWF4q3C/GO/Lsbr6OgwwwSSDESu99p7qbssIvMwqvvaGvGquOtMa8xexjotAzSd7i+q2D0O8bqxDE03tr0erim/Q3vorMdNNfxt1F8fenLABNZtMQMLYiuz111vMenHVSs87wNxlC822FriKHbewDqsN7d145+333jvTLSzggd+bc9lkEy7s2T9rnSriWQSst+P+mN87N8OoUo6FtjfDnbnS+G4etOdX7Hwz5KMb6/DconeOehXUGl1366z6y+3ts9M+M+u4u52zrL1TEfzxUBcvRce3I+84Absqv/zqwDtP+q8CzA2A9FEAAH3oi1tvbOnZZ88w91AsbYDR7oov7NzZIyw7+k2wun7z7qeb8uT0158/8v3z3/+CF0AmDPB4BVzCAQmYwCSwL3RYWyCqBiCAClowew10YM42OK4IShAAFCyfBQeQQSR8cHQlPMIJM5dCI6wQcy0swgsdF0Mi2K96J/xVBGs4BPV9D3ozBOEIh3cqHgrBex3MGfTw9z+BXVAAsDJiEJC4RCZ+cFwhzJb+FIHAvCCOL1hb/IEX4xZGHxyrcQsEGvHKyANqrQ+H+UNABbtmNzbm4GdvXKIEt2XBwdlxB9qCnh5XqC4LSuyPOgiYIK34PzlWkGOIvOPSzsa4jx0vdqfKFRT5F0kb8O0AB5AY7hzJyE7e4FYEAOUBGBk3AwjgAAKwoik9eSxVsnJvrqwgE2dZA1mlEpRou6X91JZLAXiwiLycwcxsqaoBHGBgeytA9rRlweYlU5mtcuYqrQZKUYoLlEFz5QEsOb9rviBW/iobKKEJMgF4s1/uXFgFf7a/OpqzBVkD58Kemap5XoufS3vlO5N3TxdYDJQf0yeqQBkwWCoLlsIs6Dn+n1UAYDZ0mws9QMMUmslHTkuiBnVdNx/KTgAwdJ8fQwBEYQbSFUyLc/0cp6pO+s+SAoBfh2upSx1nAI6eiqaZtCgNdaoCzP1yVUA91SuPOTKiosCom5ypRpVlTBg69anO8ycAr8rVrnr1q2ANq1jHStaymvWsaE2rWtfK1ra69a1wjatc50rXutr1rnjNq173yte++vWvgA2sYAdL2MIa9rCITaxiF8vYxjr2sZCNrGQnS9nKWvaymM2sZjfL2c569rOgDa1oR0va0pr2tKhNrWpXy9rWuva1sI2tbGdL29ra9ra4za1ud8vb3vr2t8ANrnCHS9ziGve4yE2ucpfHy9zmOve50I2udKdL3epa97rYza52t8vd7nr3u+ANr3jHS97ymve86E2vetfL3va6973wja9850vf+tr3vvjNr373y9/++ve/AA6wgAdM4AIb+MAITrCCF8zgBjv4wRCOsIQnTOEKW/jCGM6whjfM4Q57+MMgDrGIR0ziEpv4xChOsYpXzOIWu/jFMI6xjGdM4xrb+MY4zrGOd8zjHvv4x0AOspCHTOQiG/nISE6ykpfM5CY7+clQjrKUp0zlKlv5yljOMlgjAAAh+QQBCgAPACwAAAAAMANYAgAE/vDJSau9OOvNu/9gKI5kaZ5oqq5s675wLM90bd94ru987//AoHBILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/4LB4TC6bz+i0es1uu9/wuHxOr9vv+Lx+z+/7/4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NHS09TV1tfY2drb3N3e3+Dh4uPk5ebn6Onq6+zt7u/w8fLz9PX29/j5+vv8/f7/AAMKHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8eP/iBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1izat3KtavXr2DDih1LtqzZs2jTql3Ltq3bt3Djyp1Lt67du3jz6t3Lt6/fv4ADCx5MuLDhw4gTK17MuLHjx5AjS55MubLly5gza97MubPnz6BDix5NurTp06hTq17NurXr17Bjy55Nu7bt27hz697Nu7fv38CDCx9OvLjx48iTK1/OvLnz59CjS59Ovbr169iza9/Ovbv37+DDix9Pvrz58+jTq1/Pvr379/Djy59Pv779+/jz69/Pv7///wAG/ijggAQWaOCBCCao4IIMNujggxBGKOGEFFZo4YUYZqjhhhx26OGHIIYo4ogklmjiiSimqOKKLLbo4oswxijjjDTWaOONOOao44489ujjj0AGKeSQRBZp5JFIJqnkkkw26eSTUEYp5ZRUVmnllVhmqeWWXHbp5ZdghinmmGSWaeaZaKap5ppstunmm3DGKeecdNZp55145qnnnnz26eefgAYq6KCEFmrooYgmquiijDbq6KOQRirppJRWaumlmGaq6aacdurpp6CGKuqopBYCwKmopqrqqqymigACrcYqq6qlpjHrrQYgQMCqBRSwKgEFGHDrrLWiMaysucKqKgK+/i4b7LGxFnsGtK3m+myqBSiLqgG9CkvtqtKaQS0Bu6pKgK7Ompvtqq9SG24Z0CbrLarnantqr86WSy++0L5LxrivzguAvK42eyq31x7cq77D+jsGtckyDACzAq+7rcX7Jtyww2F8SzC96KLa7r72IizxrRx3XC0BAp96LsO5tqxqrgwza+/BIbOaMhixvtzyx99erPHAvd6c6s5fxGpAvS2/HHTBNyM89NFId4Gsz6kaoPXT27KcdQEDTE111VvMunTAXB8rtcy0kl222VinjWyvYrftdhbDns223BnvbffdV2SNwNYz+823yzJbay/geNMb9+FyAzvAAIszHnjW/vV6DXnQ3E5egMSWXz7zy5pvjisCng8ObuhVrHyu4abTO7nqOrNOBbKEx3614bbfrvvvq/cexcCwAx+018JLAQC55OZuvMfMTj4AAMkPfzbppT8vK7BgS79u9VBsq7Wu5Gp/a/dh0049+E6wunTx2jsdPPtLmA88/U3Y/zv+TOivO//185/pAKgE5hmwfAJslfQWODkCJuGABkxgq8AmgAFU0IIFcCASJAg5DR6Bg4fzoBFAyDcRFoGEcjMhEViFOqMl8FUnW58Kg7AvARzghgdAIQAmV8Fs6WuGQtghDnE4gBgKUHIVvCCsgEhDHArgczpclbUsOD0mAmF5Royi/rmEZcUfaDFoXfTB9gYAP9NxK4syDKMOqjWAGxpMfwjo4d7UyAMptvGGZEwgtwTAR/Whio47WJYNDyAANGpvjxW8GSDXiKo7HsCFEiSABZ84tkXaIFVtnFqutOdHnIWtkpakQd5uCMnDEaCC8AvlDW51SkKWkXOTLJ4qL4msGwrglU8zABVhN8sabM+GhfwaLqXoR13ykXe9nIHSbNmyTMqtAJRMlSRRGa1kyiBWbQymNG9oyFmdUpvbwmA1rQmDnuVRVTacnsgEUMqJgXOHFfwVFFtFzhh8CwE3FJgzUcVHbMVzW338Vj3LGS8bvhEANmSYDV3VT3/e0l0DdQG1/grgym0+FFU3zJoNBWbMgxIroi2IFylVRVF18jOHmIzmOi+6MZCuYKIe3SdGBUDSf2LLo7JyKQv4ZoCEqiqjWatoCnWqAr4RgJCrQio63/k0ohY1cirFKEr92c2POvUExrPp/67K1a569atgDatYx0rWspr1rGhNq1rXyta2uvWtcI2rXOdK17ra9a54zate98rXvvr1r4ANrGAHS9jCGvawiE2sYhfL2MY69rGQjaxkJ0vZylr2spjNrGY3y9nOevazoA2taEdL2tKa9rSoTa1qV8va1rr2tbCNrWxnS9va2va2uM2tbnfL29769rfADa5wh0vc4hr3uMhNrnKXx8vc5jr3udCNrnSnS93qWve62M2udrfL3e5697vgDa94x0ve8pr3vOhNr3rXy972uve98I2vfOdL3/ra9774za9+98vf/vr3vwAOsIAHTOACG/jACE6wghfM4AY7+MEQjrCEJ0zhClv4whjOsIY3zOEOe/jDIA6xiEdM4hKb+MQoTrGKV8ziFrv4xTCOsYxnTOMa2/jGOM6xjnfM4x77+MdADrKQh0zkIhv5yEhOspKXzOQmO/nJUI6ylKdM5Spb+cpYzjJYIwAAIfkEAQoADwAsAAAAADADWAIABP7wyUmrvTjrzbv/YCiOZGmeaKqubOu+cCzPdG3feK7vfO//wKBwSCwaj8ikcslsOp/QqHRKrVqv2Kx2y+16v+CweEwum8/otHrNbrvf8Lh8Tq/b7/i8fs/v+/+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/wADChxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHj/4gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4sfT768+fPo06tfz769+/fw48ufT7++/fv48+vfz7+///8ABv4o4IAEFmjggQgmqOCCDDbo4IMQRijhhBRWaOGFGGao4YYcdujhhyCGKOKIJJZo4okopqjiiiy26OKLMMYo44w01mjjjTjmqOOOPPbo449ABinkkEQWaeSRSCap5JJMNunkk1BGKeWUVFZp5ZVYZqnlllx26eWXYIYp5phklmnmmWimqeaabLbp5ptwxinnnHTWaeedeOap55589unnn4AGKuighBZq6KGIJqrooow26uijkEYq6aSUVmrppZhmqummnHbq6aeghirqqKQWAsCpqKaq6qqspooAAq3GKquqpaYx660GIEDAqq+uSgACBtw6a61oCCtrrrCq2quyBf4Ea2yrxJ7xbKvIOosqAgWoakABwE7LarRmTEvArqr+Su61yaL6a7rXnissuGU8a8Cv1p5qrrLsAvCquwAQUEC2z8JLxrS5ElBvtalym2quzS6MLb/DCiwGwfe62i26Cyucqr8XGyvxxAS/Wm/Fpy5rbwHubouytx+HEeu89dqrq8Mxa9uxviajmmvNqLYMRqzjGqwtvd5q+2/MKuebqs9fvGxuzEQXre7KGXPL86lMe3Fs0AcbcLWxMDt8tKxZdzHrvLp+LfWqKjdMdtlb4Mr12rj+ezO0cGshLNpq0w3AtnfjnTcWGxtcs9d+3yq0zRpjPTjhOj+d+OT9/ts4AP6PQx753JQX3TbVPWduBavzct45rtjaXbPoo5Nu+umyEjCA1YKzLsWxiMN+9uK12w6F7sB/6/vtvwavu9DD337A8gKAbry3DBcwO+bJ/17A8tgfIIDSz7P6q/TSzw5r9b+fLAD2AnR/a/j/8k7+E6wiMAD36m8cOPXvM1E/8Pk3sb/u/dPf/04XwCUMkIAFTEL2FnjAVoVvABCEYAESmITzLVB7DWTVAyM4OwoiIYOT8+ARQJg4ERqBhH4zYRFQSDcVEiF+82MhyZbmQiFMzYIYRCEHaee4GgIBANdbYAxR6C8OQnB8Pvwh+pzHwlMxLIL4S2IP+gWxJnovWFL8gf4Vi5ZFH8TObf8r2Nu6uANqDWB5APuf/Jp3NTLygG1BPMAA+qa7bUUwcG4so6ssKIAq7s9fAoAgv/Kog1SdUXv0O6DsBrA9GhLyBoY8ABh1lkjKBS5X0wvdI20gLAKcr5J+8+Qc37VJTs7Kk9qj49oMwMhRRqyUNDjl+QSgSrqxMpB9g2UNYjfLg03Scze7pSt7p8sXAK2XqjpjGqVWgOZpq5VfK+YMYnVGWpZLe340lif7+ExnElOaLADaACB2vgFsrJGxkp+7mmlOo2UTnDHwFgJSaUhvnkoA6UMVOxeGz2x+E54pINj5lgmA87kLn+fM57VwOS2AGvNZ17Omuv7oiarz8VOiTmQkQV/p0BXIy6Cq2meqcogqjZYLn7WMYkcDCtGNKlNVCN1jO1210TGulKW2PF/MSGovlJbwpipIHAF4es8DrKqfPwUqChJnAHtWVKH6HObalIpT3QXSeFTNqla3ytWuevWrYA2rWMdK1rKa9axoTata18rWtrr1rXCNq1znSte62vWueM2rXvfK17769a+ADaxgB0vYwhr2sIhNrGIXy9jGOvaxkI2sZCdL2cpa9rKYzaxmN8vZznr2s6ANrWhHS9rSmva0qE2talfL2ta69rWwja1sZ0vb2tr2trjNrW53y9ve+va3wA2ucIdL3OIa97jITa5yl8PL3OY697nQja50p0vd6lr3utjNrna3y93ueve74A2veMdL3vKa97zoTa9618ve9rr3vfCNr3znS9/62ve++M2vfvfL3/76978ADrCAB0zgAhv4wAhOsIIXzOAGO/jBEI6whCdM4Qpb+MIYzrCGN8zhDnv4wyAOsYhHTOISm/jEKE6xilfM4ha7+MUwjrGMZ0zjGtv4xjjOsY53zOMe+/jHQA6ykIdM5CIb+chITrKSl8zkJjv5yVCOspSnTOUqW9mrEQAAIfkEAQoADwAsAAAAADADWAIABP7wyUmrvTjrzbv/YCiOZGmeaKqubOu+cCzPdG3feK7vfO//wKBwSCwaj8ikcslsOp/QqHRKrVqv2Kx2y+16v+CweEwum8/otHrNbrvf8Lh8Tq/b7/i8fs/v+/+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/wADChxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHj/4gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4sfT768+fPo06tfz769+/fw48ufT7++/fv48+vfz7+///8ABv4o4IAEFmjggQgmqOCCDDbo4IMQRijhhBRWaOGFGGao4YYcdujhhyCGKOKIJJZo4okopqjiiiy26OKLMMYo44w01mjjjTjmqOOOPPbo449ABinkkEQWaeSRSCap5JJMNunkk1BGKeWUVFZp5ZVYZqnlllx26eWXYIYp5phklmnmmWimqeaabLbp5ptwxinnnHTWaeedeOap55589unnn4AGKuighBZq6KGIJqrooow26uijkEYq6aSUVmrppZhmqummnHbq6aeghirqqKQWAsCpqKaq6qqspkoAAa3GKquqpaYx660GvLoqAgisSgACBtw6a61oCCtrrrCq+quvwP4aGyuxZzjbaq7NpsqrqgbwGqy0q0JrhrS5+krAtqgu6yoCybqarrDeluEssuSemu26AFx7Lr3Z9upsu2SA+2q88J6bar4A80rvsPyK4e+4ylZ7qr3yQnzqrw6zm3AY3AaMarbx6louugNry+3FGLeKwAAHv7purvGuyvK5+g7McKskgxHrAAcIgO+/3GIrcsgFHIxqzV/EasABSLeMbM/3+lxAxbQS3cWxSB+wsgEtZ4x1yD/TLDUXsx6dNNPvIvB01lF/rQWuVaNNts9dP6v22rgKcEDMb097tsVzY5EqzgNkbW7ex2ada9Cp9p3F31UXQPjjAPxaAOJDK36Fqv4IVK0z5ExnOznUll++agGau825ywR8PnPioVfRKgE43326sKkH7Xbrrsdqtumzb7w6q7hT0fvwwAcvReR4Ew85w8YfrznlyvdM7eSONx8FAAXYXfUBAyQf/bRmUz85rNZDUW72mn9/q/joklv+E6zC7r362P5e+ftM0D88/k3o3zv/+fPf6QC4BAEOkIBJ0N72cmbAVonvgQhM4AKRJoAGsmpyAyhABjNYgAgiwYKP8+ARQEg4ERqBhHkzYRFQ+DYVEoFVZpufAH8lNBcO4XyxYyAKNUi91dlQCAAYgAJzhjIWSm6DGezVD4MAAO1lkHcWzBcPAbBEIAAgXCwU1v7WqviDLDKNiz6QVeqg2Lt5yQqMPZhW7GSoPNh173Zo3IHL0HeAApBxdtkagB6hRsU46gBzThSa/gygwQzSy49/RBXpcsbGGRYSb4jMQapIx8dsfc9+VzwZJCN5Ay0KQACNzJsb78jJTobNbgK4Y+f0aMdbldIGsyLAJ1P5PQPoMXAIe+UMjoXKgqkSfAArJO90SYNYyfKTLROi4952smVu7JamI+YuWyVEWrrqk4I0FuysubEpek2aMDAmLlX1yQG4qntiLKIi9TjHaIIznNJCADInKQBnBtGciqznwPSYzW++swXg+qQ9m7g5VH1yYALAJ6rkOU5j/fMF0soeN/4jN0+DVvCZE7UlOvf1UIAaywDYBKRCT3VQeiZPlg11ZUdZENGBBhGU5LzoQvWJuVCmbaUpIBxIC2pRbCX0l/7E6QkId8xVlfRvPCWbUFVA1I2m6qgLTekXl4oC5bGTeFTNqla3ytWuevWrYA2rWMdK1rKa9axoTata18rWtrr1rXCNq1znSte62vWueM2rXvfK17769a+ADaxgB0vYwhr2sIhNrGIXy9jGOvaxkI2sZCdL2cpa9rKYzaxmN8vZznr2s6ANrWhHS9rSmva0qE2talfL2ta69rWwja1sZ0vb2tr2trjNrW53y9ve+va3wA2ucIdL3OIa97jITa5yl8PL3OY697nQja50p0vd6lr3utjNrna3y93ueve74A2veMdL3vKa97zoTa9618ve9rr3vfCNr3znS9/62ve++M2vfvfL3/76978ADrCAB0zgAhv4wAhOsIIXzOAGO/jBEI6whCdM4Qpb+MIYzrCGN8zhDnv4wyAOsYhHTOISm/jEKE6xilfM4ha7+MUwjrGMZ0zjGtv4xjjOsY53zOMe+/jHQA6ykIdM5CIb+chITrKSl8zkJjv5yVCOspSnTOUqW9mrEQAAIfkEAQoADwAsAAAAADADWAIABP7wyUmrvTjrzbv/YCiOZGmeaKqubOu+cCzPdG3feK7vfO//wKBwSCwaj8ikcslsOp/QqHRKrVqv2Kx2y+16v+CweEwum8/otHrNbrvf8Lh8Tq/b7/i8fs/v+/+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/wADChxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHj/4gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4sfT768+fPo06tfz769+/fw48ufT7++/fv48+vfz7+///8ABv4o4IAEFmjggQgmqOCCDDbo4IMQRijhhBRWaOGFGGao4YYcdujhhyCGKOKIJJZo4okopqjiiiy26OKLMMYo44w01mjjjTjmqOOOPPbo449ABinkkEQWaeSRSCap5JJMNunkk1BGKeWUVFZp5ZVYZqnlllx26eWXYIYp5phklmnmmWimqeaabLbp5ptwxinnnHTWaeedeOap55589unnn4AGKuighBZq6KGIJqrooow26uijkEYq6aSUVmrppZhmqummnHbq6aeghirqqKQWAsCpqKaq6qqspkoAAa3GKquqpaYx660EHFDAqq+uagABBtw6a61oCCsrAgccwCusqv4iAKyxsRJ7BrStGpAss6j2muqvz1K7qrRmUDvAAKsKoKuq2qLK7bLUglsGtNaem2oBBwiALranEoBAsNs62667Y1BL7wH85ptswQCkCwC3CHOL760ABwxvsrummiwCrmKsrr7oOoswxBGH4S2yB+BrLrnZ4vsqvr/u623IIreKwAAPA3CyqwdoXG23+fqr6q8fpwozGLEOUO/HJNdMbcsfG4CAz6wO/UWsBphLsKpGo+xtti5vqy/P30rdhawEWN1wAUEvbUDDT3fdqthjy1p1vUpvTfXXaQsN9xa3xitA3nb/3DbgqO7Nd9/m6hw41R4La7gWqRqNtq+KL0510P4t4/t4FvMmm7PloKvbtuabX9Gs1QLUHXrfbbt9aummrzrwAQMQvvrP+j4N9uuwV1Gt0Z/fLmzuuxfeOxVkTy5838XrfbwUy0cf7fPQcyx99GsDQD30Vg9QgOrXM/90ARhvHwUABVidrAADVB4+4+MjQD6s5kOhbgHA1/u+sPG/WnD9T2AVAci3P+alDYBOKOD1ENgEBUqPgUxwYPQguAQJLo+CSlCf5/RnQZkV4IMgJB8GkyAAc5kwWVrrYLPIx0L5IWCESFDh6mB4BBmGjoZGsCHocFgEHVqOh0RglQt9mDDXGQ+IQcgW/kxoLx/K74ON4x0SgYC+E5aQZkR0Wv4IP0i/KVKxhOxTHhG99sRdeZGKChvj5bR3Rh+ocWttdGOsBmg77BmRVnHkwe9K6L79DZB8ecujHn+WvhKK0YFO8973gibIHZzuiuDbXyK5iMdG4mBeYOyjCv9IwCNa0gaYPOSpnOZHzA1QcZ+8JK4GIABNho4A3qsjG1NZA8SxT5Z2MwAI60hLUMrtirgMnC5jOaxe0uCX7GPZHZcGtmGKspLGjAHVgKmq9FUscDOrnDMBF80ZxCp9WHRVCYMpwHE9bJiu7KYM5vhMVl4zYeFs1QDxNbN3jnKZUlSnC0Z2S0wqblypque2zPkyfb5gaaysHCvxBdBsNdShz5SVQf4PCi1wNqyfqGLfQGu3Lfy5MmoTbQG8SvgwBAjAnhqdV/vQNa5ghlSkFXVf+hSaQgAItFkfDdtLVQA6A7DyYylVV0t3uFOeWq5sNQUAK1dF0B8WNQU9Xamqgooq+ZETpE89QfjwF76sevWrYA2rWMdK1rKa9axoTata18rWtrr1rXCNq1znSte62vWueM2rXvfK17769a+ADaxgB0vYwhr2sIhNrGIXy9jGOvaxkI2sZCdL2cpa9rKYzaxmN8vZznr2s6ANrWhHS9rSmva0qE2talfL2ta69rWwja1sZ0vb2tr2trjNrW53y9ve+va3wA2ucIdL3OIa97jITa5yl8rL3OY697nQja50p0vd6lr3utjNrna3y93ueve74A2veMdL3vKa97zoTa9618ve9rr3vfCNr3znS9/62ve++M2vfvfL3/76978ADrCAB0zgAhv4wAhOsIIXzOAGO/jBEI6whCdM4Qpb+MIYzrCGN8zhDnv4wyAOsYhHTOISm/jEKE6xilfM4ha7+MUwjrGMZ0zjGtv4xjjOsY53zOMe+/jHQA6ykIdM5CIb+chITrKSl8zkJjv5yVCOspSnTOUqW/nKWM6ylrc81ggAADs=" data-'; } ?>src="<?php echo $img->OriginalURL; ?>" />
								<?php if($img->Caption) { ?>
								<p class="flex-caption">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $img->Caption; ?></p>
								<?php } ?>
							</div>
						</li>
					<?php $imgCount++; } ?>
					</ul>
				</div>
				<div id="carousel" class="flexslider bapi-flexslider" data-options='{ "animation": "slide", "controlNav": false, "animationLoop": false, "slideshow": false, "itemWidth": 50, "itemMargin": 10, "asNavFor": "#slider" }'>
					<ul class="slides">
					<?php foreach($data->Images as $img) { ?>
						<li><img alt="" src="<?php echo $img->ThumbnailURL; ?>" height="50" /></li>
					<?php } ?>
					</ul>
				</div>

		 
		<!-- Modal -->
		<div id="fullScreenSlideshow" class="modal hide fade fullScreenSlideshow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-body">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <div id="fullScreenCarousel" class="carousel slide fullScreenCarousel" data-interval="10800000">
		  <!-- Carousel items -->
		<div class="carousel-inner">
			<?php foreach($data->Images as $img) { ?>
			<div class="item" data-caption="<?php echo $img->Caption; ?>" data-imgurl="<?php echo $img->OriginalURL; ?>"><div class="carousel-caption"><h4 class="headline"><?php echo $img->Headline; ?></h4><p><?php echo $img->Caption; ?></p></div></div>
			<?php } ?>
		</div>
		<!-- Carousel nav -->
		<div class="carousel-controls"><a class="carousel-control left" href="#fullScreenCarousel" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#fullScreenCarousel" data-slide="next">&rsaquo;</a></div>
		</div>
		</div>
		</div>


			</div>
		</div>
		</section>
		<section class="row-fluid item-info module shadow-border">
		<div class="span12">
			<div class="tabbable">

			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab"><?php echo $translations['General']; ?></a></li>

				<?php 
				if($settings['propdetailrateavailtab'] != 'on') {
					if($settings['propdetailratestable'] != 'on') {
						$tab2 = $translations['Rates & Availability'];
					} else {
						$tab2 = $translations['Availability'];
					} 
				} else {
					if($settings['propdetailratestable'] != 'on') {
						$tab2 = $translations['Rates'];
					}
				}

				if($tab2) {
					echo "<li><a href='#tab2' data-toggle='tab'>$tab2</a></li>";
				}

				?>

				

				<li><a href="#tab3" data-toggle="tab"><?php echo $translations['Amenities']; ?></a></li>
				<li><a href="#tab4" id="tabs4" data-toggle="tab"><?php echo $translations['Attractions']; ?></a></li>
				<?php if($settings['propdetail-reviewtab']) { ?>	
				<li><a href="#tab5" data-toggle="tab"><?php echo $translations['Reviews']; ?></a></li>
				<?php } ?>
			</ul>		

			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
				<div class="row-fluid">
				<div class="span12 box-sides">
					<div class="row-fluid">	
						<div class="span4 property-detail module shadow-border2">
							<div class="pd">
							<h4><?php echo $translations['Property Details']; ?></h4>
							<ul class="unstyled">
								<?php if($data->Development) { ?><li><?php echo $translations['Development']; ?>: <span><?php echo $data->Development; ?></span></li><?php } ?>
								<?php if($data->Type) { ?><li><?php echo $translations['Category']; ?>: <span><?php echo $data->Type; ?></span></li><?php } ?>
								<?php if($data->Beds) { ?><li><?php echo $translations['Beds']; ?>: <span><?php echo $data->Beds; ?></span></li><?php } ?>
								<?php if($data->Bathrooms) { ?><li><?php echo $translations['Baths']; ?>: <span><?php echo $data->Bathrooms; ?></span></li><?php } ?>
								<?php if($data->Sleeps) { ?><li><?php echo $translations['Sleeps']; ?>: <span><?php echo $data->Sleeps; ?></span></li><?php } ?>
								<?php if($data->Stories) { ?><li><?php echo $translations['Stories']; ?>: <span><?php echo $data->Stories; ?></span></li><?php } ?>
								<?php if($data->Floor) { ?><li><?php echo $translations['Floor']; ?>: <span><?php echo $data->Floor; ?></span></li><?php } ?>
								<?php if($data->AdjLivingSpace) { ?><li><?php echo $translations['Unit Size']; ?>: <span><?php echo $data->AdjLivingSpace." ".$data->AdjLivingSpaceUnit; ?></span></li><?php } ?>
								<?php if($data->LotSize) { ?><li><?php echo $translations['Lot Size']; ?>: <span><?php echo $data->LotSize." ".$data->LotSizeUnit; ?></span></li><?php } ?>
								<?php if($data->GarageSpaces) { ?><li><?php echo $translations['Garage Spaces']; ?>: <span><?php echo $data->GarageSpaces; ?></span></li><?php } ?>
								<?php if($settings['averagestarsreviews']) { ?>
									<?php if($data->NumReviews > 0) { ?>
						 				<div class="starsreviews"><div id="propstar-<?php echo $data->AvgReview; ?>"><span class="stars"></span><i class="starsvalue"></i></div></div>	
									<?php } ?>
								<?php } ?>
							</ul>
							</div>
						</div>
						<div class="span8">
							<?php echo preg_replace("/\brn\b/", "", $data->Description); ?>	
						</div>
					</div>
				
				</div>
				</div>
				</div>
					<div class="tab-pane" id="tab2">
					<div class="row-fluid">
					<div class="span12 box-sides">

					<?php
					if($settings['propdetailrateavailtab'] != 'on') { ?>

						<h3><?php echo $translations['Availability']; ?></h3>
						<div id="avail" class="bapi-availcalendar" data-options='{ "availcalendarmonths": <?php echo isset($settings['propdetail-availcal']) ? $settings['propdetail-availcal'] : 3; ?>, "numinrow": 3 }' data-pkid="<?php echo $data->ID; ?>" data-rateselector="bapi-ratetable"></div>
							
						<?php 
						if($settings['propdetailratestable'] != 'on') { echo '<hr />'; }					
					} 

					if($settings['propdetailratestable'] != 'on') { ?>
						<h3><?php echo $translations['Rates']; ?></h3>

						<?php if($context->Rates->Values) { ?>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<?php foreach($context->Rates->Keys as $key) {
										echo "<th>".$key."</th>";
									} ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach($context->Rates->Values as $value) { ?>
								<tr>
									<?php foreach($value as $v) { echo "<td>".$v."</td>"; } ?>		
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php } else {
							echo $translations['No rates available'];
						} ?>
					<?php }
					?>

					</div>
					</div>
					</div>
				<div class="tab-pane" id="tab3">
				<div class="row-fluid">
				<div class="span12 box-sides">
				<h3><?php echo $translations['Amenities']; ?></h3>
				<?php foreach($data->Amenities as $amenity) { ?>		
					<ul class="amenities-list unstyled clearfix">
						<li class="category-title"><?php echo $amenity->Key; ?></li>
						<?php foreach($amenity->Values as $value) { ?>
							<li><span class="halflings ok-sign"><i></i><?php echo $value->Label; ?></span></li>
						<?php } ?>		
					</ul>
					<div class="clearfix"></div>
				<?php } ?>
				</div>
				</div>
				</div>
				<div class="tab-pane" id="tab4">			
				<div class="row-fluid">
				<div class="span12 box-sides">
					<div id="poi-map-prop" class="bapi-map" data-loc-selector='.poi-map-location' data-refresh-selector='#tabs4' data-refresh-selector-event='shown' data-link-selector='.poi-map-item' style="width:100%; height:400px;"></div>			
					<div id="map-side-bar">
					<table class="table table-bordered table-striped poi-map-locations">
					<thead>
					<tr>
						<th></th>
						<th><?php echo $translations['Attractions']; ?></th>
						<th><?php echo $translations['Category']; ?></th>
						<th><?php echo $translations['Distance']; ?></th>
					</tr>
					</thead>
					<tbody id="map-locations">
					<tr>
						<td>
						<div class="poi-map-location" data-jmapping='{ "id": <?php echo $data->ID; ?>, "point": { "lng": <?php echo $data->Longitude; ?>, "lat": <?php echo $data->Latitude; ?> }, "category" : "property"}'>
							<a class="poi-map-item mapmarker-prop" href="#"><?php echo $translations['Property']; ?></a>
							<div class="info-html">
								<div class="marker-infowindow">

									<span class="prop-image pull-left"><img src="<?php echo get_template_directory_uri(); ?>/insta-common/images/loading.gif" data-src="<?php echo $data->PrimaryImage->ThumbnailURL; ?>" caption="<?php echo $data->PrimaryImage->Caption; ?>" alt="<?php echo $data->PrimaryImage->Caption; ?>"></span>

									<span class="prop-location pull-left">
										<span>
										<?php if($data->SEO->Keyword) { ?><a target="_blank" href="<?php echo $data->DetailURL; ?>"><?php } ?>
										<b><?php echo $data->Headline; ?></b>
										<?php if($data->SEO->Keyword) { ?></a><?php } ?><br/>			
										<?php if($data->Type) { ?><b><?php echo $translations['Category']; ?>:</b><?php echo $data->Type; ?><br/><?php } ?>
										<?php if($data->City) { ?><b><?php echo $translations['City']; ?>: </b><?php echo $data->City; ?><br/><?php } ?>
										<?php if($data->Beds) { ?><b><?php echo $translations['Beds']; ?>: </b><?php echo $data->Beds; ?><br/><?php } ?>
										<?php if($data->Bathrooms) { ?><b><?php echo $translations['Baths']; ?>: </b><?php echo $data->Baths; ?><br/><?php } ?>
										<?php if($data->Sleeps) { ?><b><?php echo $translations['Sleeps']; ?>: </b><?php echo $data->Sleeps; ?><?php } ?>
										</span>
									</span>
								</div>
							</div>
						</div>
						</td>
						<td><?php echo $data->Headline; ?></td>
						<td><?php echo $data->Type; ?></td>
						<td>-</td>
					</tr>
					<?php foreach($data->ContextData->Attractions as $attraction) { ?> 
					<tr>
						<td>
						<div class="poi-map-location" data-jmapping='{ "id": <?php echo $attraction->ID; ?>, "point": { "lng": <?php echo $attraction->Longitude; ?>, "lat": <?php echo $data->Latitude; ?> }, "category":"poi-<?php echo $attraction->ContextData->ItemIndex; ?>" }'>
							<a class="poi-map-item mapmarker-<?php echo $attraction->ContextData->ItemIndex; ?>" href="#"><?php echo $attraction->ContextData->ItemIndex; ?></a>
							<div class="info-html">
								<div class="marker-infowindow"> 

									<span class="prop-image pull-left"><img src="<?php echo get_template_directory_uri(); ?>/insta-common/images/loading.gif" data-src="<?php echo $attraction->PrimaryImage->ThumbnailURL; ?>" caption="<?php echo $attraction->PrimaryImage->Caption; ?>" alt="<?php echo $attraction->PrimaryImage->Caption; ?>"></span>

									<span class="prop-location pull-left">
										<span>
										<?php if($attraction->ContextData->SEO->Keyword) { ?><a target="_blank" href="<?php echo $attraction->ContextData->SEO->DetailURL; ?>"><?php } ?>
										<b><?php echo $data->Name; ?></b>
										<?php if($attraction->ContextData->SEO->Keyword) { ?></a><?php } ?><br/>
										<?php if($attraction->Type) { ?><b><?php echo $translations['Category']; ?>:</b><?php echo $attraction->Type; ?><br/><?php } ?>
										<?php if($attraction->Location) { ?><b><?php echo $translations['Address']; ?>: </b><?php echo $attraction->Location; ?><?php } ?>
										</span>
									</span>
							  </div>
							</div>
						</td>
						<td><?php if($attraction->ContextData->SEO->Keyword) { ?><a target="_blank" href="<?php echo $attraction->ContextData->SEO->DetailURL; ?>"><?php } ?><?php echo $attraction->Name; ?>
							<?php if($attraction->ContextData->SEO->Keyword) { ?></a><?php } ?></td>
						<td><?php echo $attraction->Type; ?></td>
						<td><?php echo $attraction->ContextData->Distance; ?></td>  
					</tr>
					<?php } ?>
					</tbody>
					</table>
					</div>
				</div>
				</div>
				</div>

				<?php if($settings['propdetail-reviewtab']) { ?>
				<div class="tab-pane" id="tab5">
					<div class="row-fluid">
						<div class="span12 box-sides">
							<?php if(!$data->ContextData->Reviews) { ?>
							<h5><?php _e('There are no reviews at this time.'); ?>
							<?php } else { ?>
								<div class="clearfix"></div>
								<?php foreach($data->ContextData->Reviews as $review) { ?>
									<div class="row-fluid review">
										<div class="span2 left-side">
											<span class="glyphicons chat" href=""><i></i></span>
											<h5 class="username"><?php echo $review->ReviewedBy->FirstName." ".$review->ReviewedBy->LastName; ?></h5>
										</div>
										<div class="span10">
											<h5 class="title"><?php echo $review->Title; ?></h5>
											<div class="rating"><span class="reviewrating-<?php echo $review->Rating; ?>"></span> <span><?php echo $translations['Posted on']; ?>: <?php echo $review->SubmittedOn->ShortDate; ?></span></div>
											<div class="comment">
											<?php echo $review->Comment; ?>
											</div>
											<?php foreach($review->Response as $response) { ?>
											<div class="response-block">
												<h5 class="response-title"><?php echo $translations['Response']; ?></h5>
												<div class="response"><?php echo $response; ?></div>
											</div>
											<?php } ?>
											<?php foreach($review->ExternalLink as $link) { ?>				
											<a class="full-rev-link" href="<?php echo $link; ?>" target="_blank"><?php echo $translations['See full review on']; ?> Flipkey</a>
											<?php } ?>
											<?php if($review->Source == 'FlipKey') {
												echo '<a class="flipkeyPowered" rel="nofollow" target="_blank" href="//www.flipkey.com"><span></span></a>';
											} ?>
										</div>
									</div>
								<hr/>
								<?php } ?>
							<?php } ?>
							</div>
						</div>					
					</div>
				</div>
				<?php } ?>
				
			</div>
		</div>
	</section>

<?php } ?>

</article>

<aside class="span3">

	<?php if ( is_active_sidebar( 'insta-right-sidebar-prop-detail' ) ) : ?>		
	    <?php dynamic_sidebar( 'insta-right-sidebar-prop-detail' ); ?>		
	<?php endif; ?>

	<?php /*
	<span class="end"></span>
	<div id="bapi-rateblock" class="bapi-rateblock" data-templatename="tmpl-search-rateblock" data-log="1"></div>
	<div class="bapi-moveme" data-from="#bapi-rateblock" data-to=".detail-overview-target" data-method="append"></div>
  	*/ ?>
        
</aside>
		

<?php /*
	<div id="primary" class="content-area">
		<h1>Property page template!</h1>
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
*/ ?>


<?php
get_footer();

<div class="footer">
	<span class="copyright">&copy; {{{site.SolutionCopyright}}}</span>
    
    <div class="top-links">
	  <span id="cnt-phone"><span class="primaryphone"><span class="halflings white phone"><i></i>{{site.PrimaryPhone}}</span></span></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="currencyselector"></span>
      {{#site.HasMultiSites}}
      &nbsp;&nbsp;|&nbsp;&nbsp;<span id="cnt-siteselector"><div class="siteselector">
			{{#site.Sites}}
            
			<a href='http://{{Url}}'>
            <i class="flag flag-{{Language}}"><span style="display:none">{{RegionInfo.DisplayName}}</span></i></a>
			{{/site.Sites}}
            </div></span>
			{{/site.HasMultiSites}}&nbsp;&nbsp;|&nbsp;&nbsp;<span id="wishListLink"><a class="halflings white heart-empty" href="/rentalsearch/mylist"><i></i><span class="hidden-phone">My WishList</span></a></span>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		BAPI.UI.createCurrencySelectorWidget('.currencyselector');
		$( '.siteselector .flag' ).each(function(i) {
			var theClassnames = $(this).attr('class');
			theClassnames = theClassnames.substring(0, theClassnames.length - 3);
			$(this).addClass(theClassnames);
		});
	});
</script>

    
    <div class="footer-links">
    <span class="poweredby"><a rel="nofollow" href="http://{{site.PoweredBy.Data}}" target="_blank">{{site.PoweredBy.Label}}</a></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="applogin"><a rel="nofollow" href="{{site.App.Data}}">{{site.App.Label}}</a></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="terms"><a href="/termsofuse" target="_blank">{{#textdata.Terms}}{{textdata.Terms}}{{/textdata.Terms}}{{^textdata.Terms}}Terms{{/textdata.Terms}}</a></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="privacy"><a href="/privacypolicy" target="_blank">{{#textdata.Privacy}}{{textdata.Privacy}}{{/textdata.Privacy}}{{^textdata.Privacy}}Privacy{{/textdata.Privacy}}</a></span>
	</div>
	
<div class="clearfix"></div>		
</div>

<div class="top-links">
	  <span id="cnt-phone"><span class="primaryphone"><span class="halflings black phone">{{site.PrimaryPhone}} <i class="hidden-phone"></i></span></span></span>
      <br />
      {{#site.HasMultiSites}}&nbsp;&nbsp;<span id="cnt-siteselector"><div class="siteselector">
			{{#site.Sites}}            
			<a href='http://{{Url}}'>
            <i class="flag flag-{{Language}}"><span style="display:none">{{RegionInfo.DisplayName}}</span></i></a>
			{{/site.Sites}}
            </div></span>
			{{/site.HasMultiSites}}&nbsp;&nbsp;|&nbsp;&nbsp;<span class="currencyselector"></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span id="wishListLink"><a class="halflings white heart-empty" href="/rentalsearch/mylist"><i></i><span class="hidden-phone">{{textdata.My WishList}}</span></a></span>
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
<div class="top-links">
	  <span id="cnt-phone"><span class="primaryphone"><span class="halflings white phone"><i class="hidden-phone"></i>{{site.PrimaryPhone}}</span></span></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="currencyselector"></span>
      {{#site.HasMultiSites}}
      &nbsp;&nbsp;|&nbsp;&nbsp;<span id="cnt-siteselector"><div class="siteselector">
			{{#site.Sites}}
            
			<a href='http://{{Url}}'>
            <i class="flag flag-{{Language}}"><span style="display:none">{{RegionInfo.DisplayName}}</span></i></a>
			{{/site.Sites}}
            </div></span>
			{{/site.HasMultiSites}}&nbsp;&nbsp;|&nbsp;&nbsp;<span id="wishListLink"><a class="halflings white heart-empty" href="/rentalsearch/mylist"><i></i><span class="hidden-phone">{{textdata.My WishList}}</span></a></span>
</div>
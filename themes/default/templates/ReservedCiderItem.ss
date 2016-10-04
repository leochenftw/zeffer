<div id="$Id" class="row new-cider reserved-range clearfix">
	
	<div class="group clearfix"<% if $BorderImage %> style="background: url($BorderImage.SetWidth(960).URL) no-repeat center top;"<% end_if %>>
		<div class="grid_4 alpha">
			$Image.SetWidth(300)
		</div>
		<div class="grid_8">
			<% if $SoldOut %>
				<div class="sold-out"><Strong>[SOLD OUT]</strong></div>
			<% end_if %>
			<div class="reserve-range-heading">
				<h2 class="no-indent heading-item" data-name="Name">$Title</h2>
				<div class="product-style heading-item" data-name="Style">$ProductStyle</div>
				<div class="product-vintage heading-item" data-name="Vintage">$ProudctVintage</div>
				<div class="product-signature heading-item" data-name="Cider Maker"><% if $ProductSignature %>$ProductSignature<% else %><img src="/assets/Uploads/cider-signature.png" alt="product signature" /><% end_if %></div>
			</div>
			<div class="intro">
				$Content
			</div>
			<div class="specs clearfix">
				<div class="spec vintage-style-try-with full-width no-float clearfix">
					<div class="sub-spec spec-vintage">
						<h4>Vintage</h4>
						<p>$ProudctVintage</p>
					</div>
					<div class="sub-spec spec-style">
						<h4>Style</h4>
						<p>$ProductStyle</p>
					</div>
					<div class="sub-spec spec-try-with">
						<h4>Try me with</h4>
						<p>$TryMeWith</p>
					</div>
				</div>
				<div class="spec title-full-width grid-availability clearfix">
					<h4>Available in</h4>
					<div class="rects clearfix">
						$Availabilities
					</div>
				</div>
			</div>
		</div>
		<div class="alcohol-wrapper">{$Alchohol}% ALC</div>
	</div>
</div>
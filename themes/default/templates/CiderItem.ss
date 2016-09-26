<div id="$Id" class="row new-cider clearfix">
	<% if $CiderColour %>
	<style>
		#{$Id}.new-cider .grid_8 .spec {
			color: $CiderColour;
		}
		
		#{$Id}.new-cider .grid_8 .spec,
		#{$Id}.new-cider .grid_8 .spec.title-full-width h4,
		#{$Id}.new-cider .grid_8 .spec.title-full-width .circles .col,
		#{$Id}.new-cider .grid_8 .spec.title-full-width .circles .col .circle,
		#{$Id}.new-cider .grid_8 .spec.title-full-width .rects .rect,
		#{$Id}.new-cider .grid_8 .spec.iconized .icon {
			border-color: $CiderColour;
		}
		#{$Id}.new-cider .grid_8 .spec.iconized .icon:after {
			background-color: $CiderColour;
		}
		
	</style>
	<% end_if %>
	
	<!-- <div class="group clearfix"<% if $BorderImage %> style="background: url($BorderImage.SetWidth(960).URL) no-repeat center top, url($BorderImage.SetWidth(960).URL) no-repeat center bottom;"<% end_if %>> -->
	<div class="group clearfix">
		<div class="grid_4 alpha">
			$Image.SetWidth(300)
		</div>
		<div class="grid_8">
			<% if $TitleImage %>
				<p class="heading-image">$TitleImage.SetWidth(620)</p>
			<% end_if %>
			<% if $SoldOut %>
			<div class="sold-out"><Strong>[SOLD OUT]</strong></div>
			<% end_if %>
			<div class="intro">
				<h3>$Subtitle</h3>
				$Content
			</div>
			<div <% if $CrossJson %>data-cross-json="$CrossJson" <% end_if %>class="specs clearfix">
				<div class="spec iconized grid-see clearfix">
					<div class="icon"></div>
					<div class="detail">
						<h4>See</h4>
						<p>$See</p>
					</div>
				</div>
				<div class="spec iconized grid-smell clearfix">
					<div class="icon"></div>
					<div class="detail">
						<h4>Smell</h4>
						<p>$Smell</p>
					</div>
				</div>
				<div class="spec iconized grid-taste clearfix">
					<div class="icon"></div>
					<div class="detail">
						<h4>Taste</h4>
						<p>$Taste</p>
					</div>
				</div>
				<div class="spec iconized grid-try-me-with clearfix">
					<div class="icon"></div>
					<div class="detail">
						<h4>Try me with</h4>
						<p>$TryMeWith</p>
					</div>
				</div>
				<div class="spec title-full-width grid-dryness clearfix">
					<h4>Dryness</h4>
					<div data-value="$Dryness" class="circles clearfix">
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
					</div>
				</div>
				<div class="spec title-full-width grid-tannin clearfix">
					<h4>Tannin</h4>
					<div data-value="$Tannin" class="circles clearfix">
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
						<div class="col relative"><div class="circle"></div></div>
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
	</div>
</div>
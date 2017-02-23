<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<% base_tag %>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		$MetaTags(true)
		<% include OG %>
		<% if isTablet %>
		<meta name="viewport" content="width=1024">
		<% else %>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
		<% end_if %>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="apple-touch-icon" sizes="76x76" href="$ThemeDir/img/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="$ThemeDir/img/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="$ThemeDir/img/touch-icon-ipad-retina.png">
		<!-- <link rel="stylesheet" type="text/css" href="//cloud.typography.com/7419852/784882/css/fonts.css" /> -->
		<link rel="stylesheet" type="text/css" href="/themes/default/fonts/fonts.css" />
		$getCSS
		<script src="$ThemeDir/js/lib/modernizr.min.js" defer="defer"></script>
		<% include GA %>
	</head>
	<body class="page-$URLSegment<% if isMobile %> mobile<% end_if %><% if isTablet %> tablet<% end_if %><% if $NotificationMode %> notification-bar<% end_if %>">
		$Layout
        <% if $PopupAds && $PopupOn %>
            <% if not $NotificationMode %><div id="overlay-tray"></div><% end_if %>
            <div class="pop-up">
                <% if $NotificationMode %>
                    <button class="button close">+</button>
                    <span>$PopupTitle</span>
                    <a href="$PopupURL"<% if $TargetBlank %> target="_blank"<% end_if %>>Learn more ›</a>
                <% else %>
                    <a href="$PopupURL"<% if $TargetBlank %> target="_blank"<% end_if %>>$PopupAds.SetWidth(640)</a>
                    <div class="options">
                        <label for="show-no-more"><input id="show-no-more" type="checkbox" /> Don't show again</label>
                        <button class="button close">Close</button>
                    </div>
                <% end_if %>
            </div>
        <% end_if %>
		$getRequireJS
	</body>
</html>

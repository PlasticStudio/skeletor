<% include DocumentHead %>
	<header class="page-header">
		<div class="inner">
			<a class="branding" href="{$BaseURL}">
				<img src="{$ClientAssetsPath}/images/logo.png" alt="{$SiteConfig.Title} logo" />
			</a>
			<% include Menu %>
		</div>
	</header>
	<% include Banner %>
	$Layout
<% include Footer %>
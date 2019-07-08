<% if Resources %>
	<div class="resources resources__all cf" data-url="$QueryString">

		<% loop Resources %>

			<div class="resource resource__type--$Type" data-id="$ID">
		
				<div class="resource__image"<% if ShowResourceImage %> style="background-image: url($ShowResourceImage)"<% end_if %>>
					<% if FirstCategory %>
						<h4 class="resource__category">$FirstCategory.Title</h4>
					<% end_if %>
				</div>

				<% if Type != 'Image' %>
					<div class="resource__content">	
						
						<h2><a href="$Link">$Title</a></h2>
						<p>$ShortSummary.LimitWordCount(20)</p>

						<% if VideoURL %>
						
							<a href="javascript: void(0);" data-url="$VideoURL" data-shareurl="$ShareLink" class="readmore readmore__linkBox popup_trigger_video">
								<p>Watch video</p><span></span>
							</a>

						<% else %>
							
							<a href="$ResourceLink" class="readmore readmore__linkBox"><p>Read more</p><span></span></a>

						<% end_if %>
						
					</div>
				<% end_if %>

			</div>

		<% end_loop %>

	</div>
<% end_if %>

<% with Resources %>
	<% include Includes\Pagination %>
<% end_with %>
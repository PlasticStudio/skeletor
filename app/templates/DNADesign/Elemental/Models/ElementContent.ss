<% cached $ElementCacheKey %>
<div class="content-element__content<% if $Style %> $StyleVariant<% end_if %>">
	<% if Title && ShowTitle %>
        <h3 class="content-element__title">$Title</h3>
    <% end_if %>
    $HTML
</div>
<% end_cached %>
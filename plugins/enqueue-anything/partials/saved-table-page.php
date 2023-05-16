<div class="grid-container paper with-shadow">
    <form id="ajax-save-asset-form" method="post" enctype="multipart/form-data">
        <div class="Asset-Name">
            <label for="name">Name</label><br /> 
            <input name="name" type="text" value="" style="width: 600px;" placeholder="name this asset" />
        </div>
        <div class="Source-URL">
            <label for="url">Url</label><br /> 
            <input name="url" type="text" value="" style="width: 600px;" placeholder="source url of script or style" />
        </div>
        <div class="Put-In-Footer">
            <label for="footer">In Footer?</label><br /> 
            <input name="footer" type="radio" value="1" checked="checked" /> Yes <br /> 
	        <input name="footer" type="radio" value="0" /> No
        </div>
        <div class="Submit">
            <button class="blue-button" style="width: 200px;" type="submit" value="Add to Enque">Add to Enqueue</button>
        </div>
    </form>
</div>

<?php
$posts = get_posts([
    'post_type' => 'enqueue_any',
    'post_status' => 'publish',
    'numberposts' => -1
]);

if($posts) {

    $posts_meta = '<div id="saved-assets" class="grid-container-saved-assets paper with-shadow">
                    <div class="Col-1"><h5>Asset Name</h5></div>
                    <div class="Col-2"><h5>Source URL</h5></div>
                    <div class="Col-3"><h5>In Footer</h5></div>
                    <div class="Col-4"><h5>Delete</h5></div>';
    $posts_meta_col_name = "<div class='Data-Col-1'>";
    $posts_meta_col_url = "<div class='Data-Col-2'>";
    $posts_meta_col_in_footer = "<div class='Data-Col-3'>";
    $posts_meta_col_delete = "<div class='Data-Col-4'>";

    foreach ($posts as $key => $value) {
        $name = get_post_meta( $value->ID, 'ea_name' );
        $url = get_post_meta( $value->ID, 'ea_url' );
        $in_footer = get_post_meta( $value->ID, 'ea_in_footer' );
        $in_footer = $in_footer[0] == 0 ? 'No' : 'Yes';
        $do_delete = "<a id='$value->ID' class='remove-asset'>Remove</a>";

        $posts_meta_col_name .= "<div class='item-name'>$name[0]</div>";
        $posts_meta_col_url .= "<div class='item-url'>$url[0]</div>";
        $posts_meta_col_in_footer .= "<div class='item-in-footer'>$in_footer</div>";
        $posts_meta_col_delete .= "<div class='item-delete'>$do_delete</div>";
    }
    $posts_meta_col_name .= "</div>";
    $posts_meta_col_url .= "</div>";
    $posts_meta_col_in_footer .= "</div>";
    $posts_meta_col_delete .= "</div>";
    $posts_meta .= $posts_meta_col_name . $posts_meta_col_url . $posts_meta_col_in_footer . $posts_meta_col_delete . "</div>";

    echo wp_kses_post($posts_meta);
}
?>
<div class="grid-container paper with-shadow">
    <p><small>Need Help, Here is an example - <a href="https://porretto.com/enqueue-any/" target="_blank">Click Here</a></small></p>
</div>
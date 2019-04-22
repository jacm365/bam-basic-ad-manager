<?php
if ( array_key_exists('action', $_REQUEST) ) {
    if ( $_REQUEST['action'] == 'delete' ) {
        $this->delete_ad( $_REQUEST['post_id'], $_REQUEST['_wpnonce'] );
    }
}
$query = new WP_Query(array(
    'post_type' => 'bam_ad',
    'post_status' => 'publish'
));
?>
<div class="wrap">
    <h1 class="wp-heading-inline">BAM Basic Ad Manager</h1>
    <a href="admin.php?page=bam_basic_ad_manager_add_new" class="page-title-action">New Ad</a>
</div>
<div>
    <p>Insert the shortcode in the post you want to show your ad on. If the post has category <span class="color-pin black"></span> NFL, <span class="color-pin orange"></span> NBA or <span class="color-pin blue"></span> MLB the background color will change accordingly.</p>
</div>
<div>
    <table class="bam-ads-list wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <th class="manage-column column-title column-primary">Title</th>
                <th class="manage-column column-title column-primary">Shortcode</th>
                <th class="manage-column column-title column-primary">Type</th>
                <th class="manage-column column-title column-primary">Template</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $title = get_the_title();
            $content = get_the_content();
            $fields = json_decode($content);
            $class = 'class="title column-title has-row-actions column-primary"';
            echo '
            <tr>
                <td ' . $class . '>
                    <div>' . $title . '</div>
                    <div class="row-actions">
                        <span class="edit"><a href="http://localhost/bornagainmedia/wp-admin/admin.php?page=bam_basic_ad_manager_add_new&amp;post_id=' . $post_id . '">Edit</a> | </span>
                        <span class="trash"><a href="admin.php?page=bam_basic_ad_manager&post_id=' . $post_id . '&amp;action=delete&amp;_wpnonce=' . wp_create_nonce( 'delete-bam-ad_'.$post_id ) . '" class="submitdelete">Delete</a></span>
                    </div>
                </td>
                <td ' . $class . ' class="shortcode column-shortcode">
                    <span class="shortcode">
                        <input type="text" onfocus="this.select();" readonly="readonly"
                            value="[bam-ad-manager id=&quot;' . $post_id . '&quot;]" class="large-text code">
                    </span>
                </td>
                <td ' . $class . '>' . $fields->type . '</td>
                <td ' . $class . '>' . $fields->template . '</td>
            </tr>';
        }
        ?>
        </tbody>
    </table>
</div>
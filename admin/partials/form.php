<?php

    if ( array_key_exists('title', $_REQUEST) ) {
        $this->save_ad( $_REQUEST );
    }

    $post_id = ( array_key_exists('post_id', $_REQUEST) ) ? $_REQUEST['post_id'] : -1 ;
    $title = '';
    $type = '';
    $remaining_time = '';
    $bgcolor = '';
    $template = '';

    if( $post_id > 0 ) {
        $post_data = get_post( $post_id );
        $title = $post_data->post_title;
        $meta_data = json_decode($post_data->post_content);
        $type = $meta_data->type;
        $remaining_time = $meta_data->remaining_time;
        $bgcolor = $meta_data->bgcolor;
        $template = $meta_data->template;
    }
    $templates = $this->get_templates();
    $types = array( 'default', 'pick' );
?>
<div class="bam-ad-form">
    <form name="bam-ad-form" method="post" action="admin.php?page=bam_basic_ad_manager_add_new">
        <h1>New Ad</h1>
        <p></p>
        <div class="field-row">
            <div class="ad-field-label">
                <label>Title</label>
            </div>
            <div class="ad-field-input">
                <input type="text" name="title" id="title" value="<?php echo ( $post_id > 0 ) ? $title : ''; ?>" />
            </div>
        </div>
        <div class="field-row">
            <div class="ad-field-label">
                <label>Type</label>
            </div>
            <div class="ad-field-input">
                <select name="type" id="type">
                <?php 
                foreach ($types as $single_type) {
                    $selected = ( $type == $single_type ) ? 'selected' : '';
                    echo '<option ' . $selected . ' value="' . $single_type . '">' . ucfirst( $single_type ) . '</option>';
                } 
                ?>
                </select>
            </div>
        </div>
        <?php 
            $display = ( $type == 'pick' ) ? ' style="display: flex;" ' : '';
        ?>
        <div <?php echo $display ?> id="remaining-time" class="field-row">
            <div class="ad-field-label">
                <label>Remaining time</label>
                <small class="field-description">Ads of the type `Pick` have the aditional option to show a custom countdown, please use this format HH:mm:ss. Ej 34:30:00 </small>
            </div>
            <div class="ad-field-input">
                <input placeholder="HH:mm:ss" type="text" name="remaining_time" id="remaining_time" value="<?php echo ( $post_id > 0 ) ? $remaining_time : ''; ?>" />
            </div>
        </div>
        <div class="field-row">
            <div class="ad-field-label">
                <label>Background color</label>
                <small class="field-description">Use hexadecimal to set a default background color for this piece, if the post has one of the following categories (<span class="color-pin black"></span> NFL, <span class="color-pin orange"></span> NBA, <span class="color-pin blue"></span> MLB ) the background color will change accordingly.</small>
            </div>
            <div class="ad-field-input">
                <input placeholder="#ffffff" type="text" name="bgcolor" id="bgcolor" value="<?php echo ( $post_id > 0 ) ? $bgcolor : ''; ?>" />
            </div>
        </div>
        <div class="field-row">
            <div class="ad-field-label">
                <label>Template</label>
            </div>
            <div class="ad-field-input">
                <select name="template">
                <?php 
                    foreach ($templates as $single_template) {
                        $selected = ( $template == $single_template ) ? 'selected' : '';
                        echo '<option ' . $selected . ' value="' . $single_template['name'] . '">' . $single_template['name'] . '</option>';
                    } 
                ?>
                </select>
            </div>
        </div>
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>"/>
        <input type="submit" id="save" value="Save" class="button-primary" />
    </form>
</div>

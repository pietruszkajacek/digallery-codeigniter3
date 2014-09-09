<ul class="thumbnails thumbs-add-edit-gallery">
    <?php foreach ($thumbs_mini as $thumb): ?>
        <li data-image-id="<?php echo $thumb['imgs_id']; ?>" class="span1 add-edit-gallery-thumb thumbnail-mini test">
            <div class="thumbnail">
                <div style="position: relative;" class="container-thumb-mini">
                    <div class="outer-block-thumb-mini">
                        <?php
                            echo img(array('src' => $thumb_mini_config['path'] . $thumb['file_name']));
                        ?>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

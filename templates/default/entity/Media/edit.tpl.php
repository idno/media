<?php echo $this->draw('entity/edit/header');?>
<?php

if (!empty($vars['object']->inreplyto)) {
    if (!is_array($vars['object']->inreplyto)) {
        $vars['object']->inreplyto = array($vars['object']->inreplyto);
    }
} else {
    $vars['object']->inreplyto = array();
}
if (!empty($vars['url'])) {
    $vars['object']->inreplyto = array($vars['url']);
}

?>
<form action="<?php echo $vars['object']->getURL()?>" method="post" enctype="multipart/form-data">

    <div class="row">

        <div class="col-md-8 col-md-offset-2 edit-pane">
        
                <h4>

                <?php

                if (empty($vars['object']->_id)) {
                    ?><?php echo \Idno\Core\Idno::site()->language()->_('New Audio'); ?><?php
                } else {
                    ?><?php echo \Idno\Core\Idno::site()->language()->_('Edit Audio'); ?><?php
                }

                ?>
                </h4>

            <p>
                
                <label>
                    <span class="btn btn-primary btn-file">
                        <i class="fa fa-play-circle"></i> <span id="media-filename"><?php if (empty($vars['object']->_id)) { ?><?php echo \Idno\Core\Idno::site()->language()->_('Upload audio'); ?><?php } else { ?><?php echo \Idno\Core\Idno::site()->language()->_('Choose different audio'); ?><?php } ?></span> 
                        <?php echo $this->__([
                        'name' => 'media',
                        'id' => 'media',
                        'accept' => 'audio/*;video/*;capture=microphone',
                        'onchange' => "$('#media-filename').html($(this).val())",
                        'class' => 'col-md-9'])->draw('forms/input/file'); ?>
                    </span>
                </label>
                
            </p>
            <div class="content-form">
                <label for="title">
                    <?php echo \Idno\Core\Idno::site()->language()->_('Title'); ?></label>
                    <?php echo $this->__([
                            'name' => 'title',
                            'id' => 'title',
                            'placeholder' => \Idno\Core\Idno::site()->language()->_('Give it a title'),
                            'value' => $vars['object']->title,
                            'class' => 'form-control'])->draw('forms/input/input'); ?>

            </div>

            <?php echo $this->__([
                'name' => 'body',
                'value' => $vars['object']->body,
                'wordcount' => false,
                'height' => 250,
                'class' => 'wysiwyg-short',
                'placeholder' => \Idno\Core\Idno::site()->language()->_('Describe your audio'),
                'label' => \Idno\Core\Idno::site()->language()->_('Description'),
            ])->draw('forms/input/richtext')?>
            <?php echo $this->draw('entity/tags/input');?>

            <p>
                <small><a id="inreplyto-add" href="#"
                          onclick="$('#inreplyto').append('<span><input required type=&quot;url&quot; name=&quot;inreplyto[]&quot; value=&quot;&quot; placeholder=&quot;<?php echo addslashes(\Idno\Core\Idno::site()->language()->_('Add the URL that you\'re replying to')); ?>&quot; class=&quot;form-control&quot; onchange=&quot;adjust_content(this.value)&quot; /> <small><a href=&quot;#&quot; onclick=&quot;$(this).parent().parent().remove(); return false;&quot;><icon class=&quot;fa fa-times&quot;></icon> <?php echo \Idno\Core\Idno::site()->language()->esc_('Remove URL'); ?></a></small><br /></span>'); return false;"><i class="fa fa-reply"></i>
                        <?php echo \Idno\Core\Idno::site()->language()->_('Reply to a site'); ?></a></small>
            </p>


            <div id="inreplyto">
                <?php
                if (!empty($vars['object']->inreplyto)) {
                    foreach ($vars['object']->inreplyto as $inreplyto) {
                        ?>
                            <p>
                                <input type="url" name="inreplyto[]"
                                       placeholder="<?php echo \Idno\Core\Idno::site()->language()->_('Add the URL that you\'re replying to'); ?>"
                                       class="form-control inreplyto" value="<?php echo htmlspecialchars($inreplyto) ?>" onchange="adjust_content(this.value)"/>
                                <small><a href="#"
                                          onclick="$(this).parent().parent().remove(); return false;"><i class="fa fa-times"></i>
                                      <?php echo \Idno\Core\Idno::site()->language()->_('Remove URL'); ?></a></small>
                            </p>
                        <?php
                    }
                }
                ?>
            </div>

            <?php echo $this->drawSyndication('media', $vars['object']->getPosseLinks()); ?>
            <?php if (empty($vars['object']->_id)) { 
                echo $this->__(['name' => 'forward-to', 'value' => \Idno\Core\Idno::site()->config()->getDisplayURL() . 'content/all/'])->draw('forms/input/hidden');
            } ?>
            <?php echo $this->draw('content/extra'); ?>
            <?php echo $this->draw('content/access'); ?>
            <p class="button-bar ">
                <?php echo \Idno\Core\Idno::site()->actions()->signForm('/media/edit') ?>
                <input type="button" class="btn btn-cancel" value="<?php echo \Idno\Core\Idno::site()->language()->_('Cancel'); ?>" onclick="hideContentCreateForm();" />
                <input type="submit" class="btn btn-primary" value="<?php echo \Idno\Core\Idno::site()->language()->_('Publish'); ?>" />

            </p>
        </div>

    </div>
</form>
<script>

    function adjust_content(url) {
        var username = url.match(/https?:\/\/([a-z]+\.)?twitter\.com\/(#!\/)?@?([^\/]*)/)[3];
        if (username != null) {
            if ($('#title').val().search('@' + username) == -1) {
                $('#title').val('@' + username + ' ' + $('#title').val());
                //count_chars();
            }
        }
    }

    $(document).ready(function () {

        // Make in reply to a little less painful
        $("#inreplyto-add").on('dragenter', function(e) {
            var placeholder = '<?php echo addslashes(\Idno\Core\Idno::site()->language()->esc_('Add the URL that you\'re replying to')); ?>';
            e.stopPropagation();
            e.preventDefault();
            $('#inreplyto').append('<span><input required type="url" name="inreplyto[]" value="" placeholder="' + placeholder + '" class="form-control" onchange="adjust_content(this.value)" /> <small><a href="#" onclick="$(this).parent().parent().remove(); return false;"><icon class="fa fa-times"></icon> <?php echo \Idno\Core\Idno::site()->language()->esc_('Remove URL'); ?></a></small><br /></span>'); return false;
        });
    });

    $(document).ready(function(){
        // Autosave the title & body
        autoSave('entry', ['title', 'body'], {
          'body': '#<?php echo $unique_id?>',
        });
    });

</script>
<?php echo $this->draw('entity/edit/footer');?>

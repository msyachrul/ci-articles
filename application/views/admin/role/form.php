<h2 class="uk-heading-small uk-margin-remove"><?= ucwords($title); ?></h2>
<hr>
<?php if (isset($upload_error)) : ?>
    <div class="uk-alert-danger uk-animation-slide-top" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <?= $upload_error; ?>
    </div>
<?php endif; ?>
<form class="uk-form-stacked uk-padding-small" action="" method="post" autocomplete="off">
    <div class="uk-margin">
        <label class="uk-form-label" for="name"><?= ucwords($this->lang->line('name')); ?></label>
        <div class="uk-form-controls">
            <input class="uk-input uk-form-small <?= form_error('name') ? 'uk-form-danger' : '' ?>" id="name" type="text" name="name" value="<?= isset($role) ? $role->name : set_value('name'); ?>" placeholder="<?= ucwords($this->lang->line('name')); ?>..." required autofocus>
        </div>
        <?= form_error('name', '<p class="uk-text-danger uk-text-small uk-margin-small-top">', '</p>'); ?>
    </div>
    <div class="uk-margin">
        <label class="uk-form-label"><?= ucwords($this->lang->line('access_menu')); ?></label>
        <div class="uk-form-controls uk-grid-small uk-child-width-1-4@s" uk-grid>
            <?php foreach ($menus as $menu) : ?>
                <?php $exist = false; ?>
                <?php if (isset($role) && $menu->title !== 'Profile') : ?>
                    <?php foreach ($saved_menu_role as $smr) : ?>
                        <?php if ($menu->id === $smr->id) : ?>
                            <?php $exist = true; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($menu->title === 'Profile') : ?>
                    <input type="hidden" name="menu[<?= $menu->id; ?>]" value="1">
                <?php else : ?>
                    <label for="menu-<?= strtolower($menu->title); ?>"><input class="uk-checkbox" type="checkbox" id="menu-<?= strtolower($menu->title); ?>" name="menu[<?= $menu->id; ?>]" value="1" <?= $exist ? 'checked' : '' ?>> <?= ucwords($this->lang->line(strtolower($menu->title))); ?></label>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <hr>
    <div class="uk-margin">
        <div class="uk-flex uk-flex-center">
            <div>
                <button class="uk-button uk-button-primary" type="submit"><?= isset($role) ? ucwords($this->lang->line('update')) : ucwords($this->lang->line('save')); ?></button>
            </div>
            <?php if (!isset($role)) : ?>
                <div class="uk-margin-small-left">
                    <button class="uk-button uk-button-default" type="reset">Reset</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>
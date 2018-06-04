<?php
/**
 * This template is used to display comments.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$postId = sanitize_text_field($_GET['postId']);
AnyComment()->setCurrentPost($postId);

if (post_password_required($postId) || !comments_open($postId)) {
    return;
}

$classPrefix = AnyComment()->classPrefix();

//$const = get_defined_constants(true);
//var_dump($const['user']);
?>

<link rel="stylesheet" href="<?= AnyComment()->plugin_url() ?>/assets/css/comments.css">
<link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700&amp;subset=cyrillic" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.1/iframeResizer.contentWindow.min.js"></script>
<script src="<?= AnyComment()->plugin_url() ?>/assets/js/timeago.min.js"></script>

<div id="<?= $classPrefix ?>comments" class="<?= $classPrefix ?>comments-area comments-area" data-origin-limit="20"
     data-current-limit="20">
    <?php do_action('anycomment_send_comment') ?>
    <?php do_action('anycomment_notifications') ?>
    <?php do_action('anycomment_load_comments') ?>
</div>

<script>
    // Load generic comments template
    function loadComments(limit = null) {
        showLoader();

        jQuery.post('<?= AnyComment()->ajax_url() ?>', {
            action: 'render_comments',
            _wpnonce: '<?= wp_create_nonce("load-comments-nonce") ?>',
            postId: '<?= $postId ?>',
            limit: limit
        }).done(function (data) {
            jQuery('#<?= $classPrefix ?>load-container').html(data);
        }).fail(function () {
            console.log('Unable to get most recent comments');
        }).always(function () {
            hideLoader();
        });
    }

    // Load next comments if available
    function loadNext() {
        let newLimit = parseInt(jQuery('#<?= $classPrefix ?>comments').attr('data-current-limit')) + 10;

        console.log('Current limit: ' + newLimit);

        root.attr('data-current-limit', newLimit);

        loadComments(newLimit);
    }

    function getForm() {
        return jQuery('#send-comment-form') || '';
    }


    function getCommentField() {
        let form = getForm();

        if (!form) {
            return;
        }

        return form.find('[name="comment"]') || '';
    }

    // Reply to some comment
    function replyComment(el, replyTo) {
        if (!replyTo) {
            return;
        }

        let form = getForm();

        if (!form) {
            return;
        }

        let commentField = form.find('[name="comment"]') || '';
        let replyToField = form.find('[name="reply_to"]') || '';

        if (!commentField || !replyToField) {
            return;
        }

        replyToField.val(replyTo);
        commentField.focus();

        return false;
    }

    // Genetic send comment function
    function sendComment(el, formId) {

        let form = jQuery(formId) || '';

        if (!form) {
            return;
        }

        let commentField = form.find('[name="comment"]') || '';
        let postIdField = form.find('[name="post_id"]') || '';
        let actionField = form.find('[name="action"]') || '';
        let nonceField = form.find('[name="nonce"]') || '';

        if (!commentField || !postIdField || !actionField || !nonceField) {
            return;
        }

        let commentText = commentField.val().trim() || '';

        if (!commentText) {
            return null;
        }

        showLoader();

        let data = form.serialize();

        jQuery.post('<?= AnyComment()->ajax_url() ?>', data, function (response) {
            if (response.success) {
                commentField.val('');
                loadComments();
            } else {
                addError(response.error);
                hideLoader();
            }
            commentField.focus();
        }, 'json');

        return false;
    }

    /**
     * Display loader.
     */
    function showLoader() {
        let loader = getLoader();
        if (!loader) {
            return;
        }

        if (!loader.length) {
            return;
        }

        let loaderHtml = loader.show();
    }

    /**
     * Hide loader.
     */
    function hideLoader() {
        let loader = getLoader();
        if (!loader) {
            return;
        }

        if (!loader.length) {
            return;
        }

        let loaderHtml = loader.hide();
    }

    /**
     * Get loader.
     */
    function getLoader() {
        return jQuery('#<?= AnyComment()->classPrefix()?>loader');
    }

    /**
     * Add error alert.
     * @param message Message of the alert.
     */
    function addError(message) {
        addAlert('error', message);
    }

    /**
     * Add success alert.
     * @param message Message of the alert.
     */
    function addSuccess(message) {
        addAlert('success', message);
    }

    function addAlert(type, message) {
        if (!type || !message || (type !== 'success' && type !== 'error')) {
            return;
        }

        let alert = '<p class="{class}">{text}</p>'
            .replace('{class}', '<?= $classPrefix ?>-notification-' + type)
            .replace('{text}', message);
        let notifications = jQuery('#<?= $classPrefix ?>-notifications');
        notifications.html(alert);
        notifications.slideDown(300);
    }

    loadComments();

    let timeagoInstance = timeago();
    let nodes = document.querySelectorAll('.timeago-date-time');
    // use render method to render nodes in real time
    timeagoInstance.render(nodes);
</script>
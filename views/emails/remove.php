<?php
/**
 * Remco Schipper
 * Date: 12/12/14
 * Time: 08:52
 */

/** @var \services\Text $view_text */
?>

<div class="alert alert-danger" role="alert"><?php echo $view_text->resolve('global.remove.error', array('e-mail template')); ?></div>
<?php
defined('_JEXEC') or die;
?>
<table style="border-collapse: collapse;">
    <thead><?php echo $this->loadTemplate('head'); ?></thead>
    <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
    <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
</table>
<script type="text/javascript">
    window.print();
</script>
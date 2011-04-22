<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
$template_path = JPATH_COMPONENT_SITE.DS.'views'.DS.'contactmap'.DS.'contactmap.css';
if ($fp = @fopen($template_path, 'r')) {
    $csscontent = @fread($fp, @filesize($template_path));
    $csscontent = htmlspecialchars($csscontent);
} else {
    echo 'Error reading template file: '.$template_path;
}
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
        <fieldset class="adminform">
            <legend><?php echo JText::_( 'Details' ); ?></legend>
            <p><?php echo JText::_( 'CONTACTMAP_CSS_DETAIL' ); ?></p>
            <table class="adminform">
            <tr>
                <th>
                <?php echo $template_path; ?>
                <span class="componentheading">
                <?php
                echo is_writable($template_path) ? ' - <strong style="color:green;">'.JText::_( 'CONTACTMAP_CSS_WRITABLE' ).'</strong>' :'<strong style="color:red;">'.JText::_( 'CONTACTMAP_CSS_NOT_WRITABLE' ).'</strong>';?>
                </span>
                </th>
            </tr>
            <tr>
                <td>
                    <textarea style="width: 100%; height: 600px" cols="80" rows="25" name="csscontent" class="inputbox"><?php echo $csscontent; ?></textarea>
                </td>
            </tr>
        </table>
    <input type="hidden" name="option" value="com_contactmap" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="css" />
</form>
<div class="copyright" align="center">
    <br />
    <?php echo JText::_( 'CONTACTMAP_COPYRIGHT' );?>
</div>

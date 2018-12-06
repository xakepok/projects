<?php
defined('_JEXEC') or die;
$return = base64_encode(JUri::base() . "index.php?option=com_projects&view=exhibitor&layout=edit&id={$this->item->id}");
if ($this->item->id != null) {
    $addUrl = JRoute::_("index.php?option=com_projects&amp;task=person.add&amp;exbID={$this->item->id}&amp;return={$return}");
    $addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_TITLE_NEW_PERSON'));
    echo "<div>{$addLink}</div>";
}
?>
<table class="addPrice">
    <thead>
    <tr>
        <th>
            <?php echo 'ID'; ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PERSON_FIO'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PERSON_POST'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PERSON_PHONE_WORK'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PERSON_PHONE_MOBILE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PERSON_EMAIL'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PERSON_IS_MAIN'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_TITLE_EDIT_PERSON'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_TITLE_REMOVE_PERSON'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->persons as $person):
        $editUrl = JRoute::_("index.php?option=com_projects&amp;task=person.edit&amp;id={$person['id']}&amp;exbID={$this->item->id}&amp;return={$return}");
        $editLink = JHtml::link($editUrl, JText::sprintf('COM_PROJECTS_TITLE_EDIT_PERSON'));
        ?>
        <form action="<?php echo $person['action']; ?>" method="post"
              id="form_person_<?php echo $person['id']; ?>">
            <tr id="row_person_<?php echo $person['id']; ?>">
                <td style="color:<?php echo ($person['expired']) ? 'red' : 'black';?>">
                    <?php echo $person['id']; ?>
                </td>
                <td>
                    <?php echo $person['fio']; ?>
                </td>
                <td>
                    <?php echo $person['post']; ?>
                </td>
                <td>
                    <?php echo $person['phone_work']; ?>
                </td>
                <td>
                    <?php echo $person['phone_mobile']; ?>
                </td>
                <td>
                    <?php echo $person['email']; ?>
                </td>
                <td>
                    <?php echo JText::sprintf((!$person['main']) ? 'JNO' : 'JYES'); ?>
                </td>
                <td>
                    <?php echo $editLink; ?>
                </td>
                <td class="personDel_<?php echo $person['id']; ?>">
                    <input type="button" value="<?php echo JText::sprintf('COM_PROJECTS_TITLE_REMOVE_PERSON');?>" onclick="removePerson(<?php echo $person['id']; ?>);">
                </td>
            </tr>
        </form>
    <?php endforeach; ?>
    </tbody>
</table>

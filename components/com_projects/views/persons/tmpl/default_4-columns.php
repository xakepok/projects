<?php
defined('_JEXEC') or die;
?>
<?php for ($i = 0; $i < count($this->items); $i++): ?>
    <div class="row">
        <?php for ($j = 1; $j <= 4; $j++): ?>
            <?php if (!isset($this->items[$i])) {
                $i++;
                continue;
            } ?>
            <div class="col-lg-3">
                <div class="card" style="margin-bottom: 5px;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $this->items[$i]['fio']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $this->items[$i]['post']; ?></h6>
                        <p class="card-text">
                            <?php if (count($this->items[$i]['contacts']) > 0): ?>
                            <?php foreach ($this->items[$i]['contacts'] as $title => $contact) : ?>
                        <div class="row">
                            <div class="col"><?php echo $title;?></div>
                            <div class="col"><?php echo $contact;?></div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (!empty($this->items[$i]['comment'])) echo $this->items[$i]['comment'];?>
                        </p>
                        <?php echo $this->items[$i]['edit']; ?>
                    </div>
                </div>
            </div>
            <?php $i++; endfor; ?>
    </div>
<?php endfor; ?>


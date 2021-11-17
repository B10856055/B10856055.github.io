<div class="row">
    <div class="col-12">
        <ol>
             <?php foreach ($labels as $key =>$label): ?>
                <?php $garbage = array('Water','Grass','Terrestrial animal','Organism','Adaptation') ?>
                <?php if (in_array($label->info()['description'],$garbage)==FALSE){?> 
                <li> <h6> <?php echo ucfirst($label->info()['description']) ?> <h6> Confidence: <strong> <?php echo number_format($label->info()['score']*100 , 2) ?>

                </strong><br><br></li>
                <?php } ?>
             <?php endforeach ?>
        </ol>
    </div>
</div>
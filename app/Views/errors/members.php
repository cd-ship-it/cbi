

<?php if (! empty($members) && is_array($members)) : ?>

        <?php foreach ($members as $news_item): ?>

               <?php  var_dump($news_item);?>
			   
			   <br />

        <?php endforeach; ?>

<?php else : ?>

        <h3>No News</h3>

        <p>Unable to find any news for you.</p>

<?php endif ?>
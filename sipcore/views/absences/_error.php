<ul>
    <?php foreach ($errors as $error): ?>
        <?php foreach($error as $error_msg):?>
            <li><?php echo $error_msg; ?></li>    
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
<h1>Despre clasă</h1>

<p>Școala : <?php echo $school->name.' '.$school->city; ?></p>
<p>Clasa : <?php echo $class->grade.' '.$class->name; ?></p>
<p>Profil : <?php echo $class->profile; ?></p>
<h1>Diriginte</h1>
<?php $teacher = $class -> rAccount; ?>
<p><strong><?php echo $teacher->name; ?></strong></p>
<p>Telefon: <?php echo $teacher->phone; ?></p>
<p>E-mail: <?php echo $teacher->email; ?></p>
<h1>Bună ziua, <strong><?php echo $model->email; ?></strong>,</h1>

Contul dvs. SIP (http://siponline.ro) a fost creat cu succes.<br/><br/>

SIP este o platformă care are ca scop ținerea la curent a părinților cu performanțele
copiilor la școală. <?php CHtml::link('Află mai multe', 'http://sip.web2011.infoeducatie.ro/?r=site/despre'); ?>.<br />

<h2>Datele dvs. de conectare sunt următoarele:</h2>
E-mail: <strong><?php echo $model->email; ?></strong><br />
Telefon: <strong><?php echo $model->phone; ?></strong><br />
Cod de activare: <strong><?php echo $activation; ?></strong>
<br/><br/>

Vă rugăm să vă activați contul urmând legătura:
<a href="http://sip.web2011.infoeducatie.ro/?r=account/activate&amp;code=<?php echo $activation; ?>" target="_blank">http://sip.web2011.infoeducatie.ro/?r=account/activate&amp;code=<?php echo $activation; ?></a>.
Pentru activare, veți fi rugat să setați o parolă a contului, iar apoi vă veți putea conecta. După ce ați deschis contul dvs., veți putea face modificări suplimentare, de exemplu setarea orelor între care doriți să primiți SMS-uri.


<br /><br />
<br /><br />
Mulțumim pentru înțelegere,<br/>
<a href="http://sip.web2011.infoeducatie.ro">SIP online</a><br/>
<a href="mailto:contact@siponline.ro">contact@siponline.ro</a><br/>

<br/>
<em>Dacă nu doriți să folosiți acest program sau considerați că
    ați primit acest e-mail dintr-o greșeală, vă rugăm să ne anunțați
    folosind <a href='http://sip.web2011.infoeducatie.ro/index.php?r=site/contact' target='_blank'
    title="Contact SIPonline">formularul de contact</a> de pe site.</em>
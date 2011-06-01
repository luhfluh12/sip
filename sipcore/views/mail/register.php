<h1>Bună ziua, <strong><?php echo $model->email; ?></strong>,</h1>

Contul dvs. SIP a fost activat, datele de conectare sunt următoarele:<br/><br/>

E-mail: <strong><?php echo $model->email; ?></strong><br />
Telefon: <strong><?php echo $model->phone; ?></strong><br />
Parolă (generată automat): <strong><?php echo $model->getTempPassword(); ?></strong>
<br/><br/>

Vă puteți conecta cu e-mail-ul și parola sau numărul de telefon și parola oricând, urmând legătura
<a href="http://siponline.ro" target="_blank">http://siponline.ro</a>.
<br /><br />
<strong>Din motive de securitate, vă rugăm să schimbați parola.</strong>
<br /><br />
Mulțumim pentru înțelegere,<br/>
<a href="http://siponline.ro">SIP online</a><br/>
<a href="mailto:contact@siponline.ro">contact@siponline.ro</a><br/>

<br/>
<em>Dacă nu doriți să folosiți acest program sau considerați că
    ați primit acest e-mail dintr-o greșeală, vă rugăm să ne anunțați
    folosind <a href='http://siponline.ro/index.php?r=site/contact' target='_blank'
    title="Contact SIPonline">formularul de contact</a> de pe site.</em>

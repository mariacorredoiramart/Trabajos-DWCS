<?php
include 'clases/Administrativo.php';
include 'clases/AlumnadoBach.php';
include 'clases/AlumnadoESO.php';
include 'clases/AlumnadoFP.php';
include 'clases/Conserje.php';
include 'clases/PersonalDeLimpieza.php';
include 'clases/Profesorado.php';

$clasesDisponibles = [Administrativo::class, AlumnadoBach::class, AlumnadoESO::class, AlumnadoFP::class, Conserje::class, PersonalDeLimpieza::class, Profesorado::class];

for ($i = 0; $i < 100; $i++) {
        $claseAleatoria = $clasesDisponibles[array_rand($clasesDisponibles)];
        $objetos[] = $claseAleatoria::generarAlAzar();
}

$i =1;
foreach($objetos as $objeto) {
        print ("<br>----------" . $i++ . "-----------<br>");
        echo $objeto->trabajar();
}

print ("<br>---------------------<br>");

foreach($clasesDisponibles as $clase) {
        $objetosCreados = $clase::numeroObjetosCreado();
        print("<br> Se han creado ". $objetosCreados." objetos de la clase ".$clase);
}


?>
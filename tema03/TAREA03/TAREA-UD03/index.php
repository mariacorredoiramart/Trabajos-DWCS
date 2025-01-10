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
        print ("<br>--------" . ($i + 1) . "--------<br>");
        $claseAleatoria = $clasesDisponibles[array_rand($clasesDisponibles)];

        if ($claseAleatoria === Conserje::class) {
                $conserje = Conserje::generarAlAzar();
                print ($conserje->trabajar());
        } elseif ($claseAleatoria === Profesorado::class) {
                $profesorado = Profesorado::generarAlAzar();
                print ($profesorado->trabajar());
        } elseif ($claseAleatoria === AlumnadoFP::class) {
                $alumnoFP = AlumnadoFP::generarAlAzar();
                print ($alumnoFP->trabajar());
        } elseif ($claseAleatoria === AlumnadoESO::class) {
                $alumnoESO = AlumnadoESO::generarAlAzar();
                print ($alumnoESO->trabajar());
        } elseif ($claseAleatoria === AlumnadoBach::class) {
                $alumnoBach = AlumnadoBach::generarAlAzar();
                print ($alumnoBach->trabajar());
        } elseif ($claseAleatoria === PersonalDeLimpieza::class) {
                $personalLimpieza = PersonalDeLimpieza::generarAlAzar();
                print ($personalLimpieza->trabajar());
        } else if ($claseAleatoria === Administrativo::class) {
                $administrativo = Administrativo::generarAlAzar();
                print ($administrativo->trabajar());
        }

}
print ("<br>---------------------<br>");


$objetosCreados = Conserje::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." conserjes");
$objetosCreados = Profesorado::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." profesores");
$objetosCreados = AlumnadoFP::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." alumnos de FP");
$objetosCreados = AlumnadoESO::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." alumnos de la ESO");
$objetosCreados = AlumnadoBach::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." alumnos de bachiller");
$objetosCreados = PersonalDeLimpieza::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." personales de limpieza");
$objetosCreados = Administrativo::numeroObjetosCreado();
print("<br> Se han creado ". $objetosCreados." administrativos");

?>
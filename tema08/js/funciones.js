function getCoordenadas(event) {
    event.preventDefault();
    let dir = document.getElementById('dir').value;
    jaxon_getCoordenadas(dir);
    return true;
}


function ordenarEnvios(idList) {

    let tabla = document.getElementById(idList);
    let notasCoordenadas = tabla.getElementsByClassName("notasCoordenadas");

    let arrayCoordenadas = [];

    for(let i = 0; i < notasCoordenadas.length; i++){
        let value = notasCoordenadas[i].value;
        arrayCoordenadas.push(value);
    }

    jaxon_ordenarEnvios(arrayCoordenadas, idList);
}

function cargarMapa() {
    let lat = getParameterByName('lat');
    let lon = getParameterByName('lon');

    let mapa = document.getElementById('myMap');
    new Microsoft.Maps.Map(mapa, {
        center: new Microsoft.Maps.Location(lat, lon),
        mapTypeId: Microsoft.Maps.MapTypeId.canvasLight,
        zoom: 17
    });
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    let regex   = new RegExp("[\\?&]" + name + "=([^&#]*)");
    let results = regex.exec(location.search);

    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function mostrarOrdenados(arrayWpOrdenados){
    console.log(arrayWpOrdenados);
    let idLista = arrayWpOrdenados["idLista"];
    let posiciones = arrayWpOrdenados["posiciones"];
    let url = "http://localhost/TAREA_UD08/public/repartos.php?action=oEnvios&idLt="+idLista;


    for(let i = 0; i < posiciones.length; i++){
        url = url + "&pos[]="+posiciones[i];
    }

    window.location = url;
}

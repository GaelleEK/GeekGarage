// Carte des centres

// initilisation d un tableau vide pour stockées les marqueurs
var markersArray = []

// Initialisation de la carte
var mymap = L.map('mapid').setView([48.852969, 2.349903], 6)

// Chargement tuiles et config zoom
L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    attribution: 'données &copy; <a href="//osm.org/copyright">OpenStreetMap</a> ' +
        '- rendu <a href="//openstreetmap.fr">OSM France</a>',
    minZoom: 1,
    maxZoom: 20
}).addTo(mymap)

//regroupement des marqueurs
var markers = L.markerClusterGroup()

let xmlhttp = new XMLHttpRequest();

xmlhttp.onreadystatechange = () => {
    // La transaction est terminée ?
    if(xmlhttp.readyState === 4){
        // Si la transaction est un succès
        if(xmlhttp.status === 200){
            // On traite les données reçues
            let donnees = JSON.parse(xmlhttp.responseText)

            // On boucle sur les données (ES8)
            Object.entries(donnees.agences).forEach(agence => {
                // Ici j'ai une seule agence
                // On crée un marqueur pour l'agence
                let marker = L.marker([agence[1].lat, agence[1].lon])
                let href = "home/contact/"+ agence[1].id
                let popup = marker.bindPopup("<h6>"+agence[1].city+"</h6>"+"<p class='row'>"+agence[1].address+"</p>"
                    +"<a class='btn btn-primary text-white' type='submit' href="+href+">"+"Nous contacter"+"<a/>")
                markers.addLayer(marker); // On ajoute le marqueur au groupe
                // On ajoute le marqueur au tableau
                markersArray.push(marker, popup);
            })
        }else{
            console.log(xmlhttp.statusText);
        }
        // On regroupe les marqueurs dans un groupe Leaflet
        let groupe = new L.featureGroup(markersArray);

        // On adapte le zoom au groupe
        mymap.fitBounds(groupe.getBounds());

        mymap.addLayer(markers);
    }
}

xmlhttp.open("GET", "/mapinfo");

xmlhttp.send(null);
//////////////////////////////////////////////////////////////////
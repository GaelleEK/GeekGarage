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


// Ecoute des event pour afficher lat et long

let marker

window.onload = () => {
    //vois si lat et lon sont renseignés pour poser marqueur sur map
    let lat = document.querySelector("#center_form_lat").value
    let lon = document.querySelector("#center_form_lon").value

    if(lat != undefined && lon != undefined){
        marker = L.marker([lat, lon])
        marker.addTo(mymap)
    }
    // ecoute d events :

    // mymap.on("click", mapClickListen)
    document.querySelector("#center_form_city").addEventListener("blur", getAddress)

    // function mapClickListen(e){
    //     //on récupère les coo du clic
    //     let pos = e.latlng
    //
    //     //on ajoute le marqueur
    //     addMarker(pos)
    //
    //     //on affiche les coo dans le form
    //     document.querySelector("#center_form_lat").value = pos.lat
    //     document.querySelector("#center_form_lon").value = pos.lng
    // }

    function addMarker(pos){
        //on vérifie si le mark existe
        if(marker != undefined){
            mymap.removeLayer(marker)
        }

        marker = L.marker(pos, {
            //on rend le mark déplacable
            draggable: true
        })

        //on écoute le glisser déposer sur le mark
        marker.on("dragend", function (e){
            pos = e.target.getLatLng()
            document.querySelector("#center_form_lat").value = pos.lat
            document.querySelector("#center_form_lon").value = pos.lng
        })

        marker.addTo(mymap)
    }

    function getAddress(){

        //on fabrique l adresse
        let address = document.querySelector("#center_form_address").value +
            " " + document.querySelector("#center_form_city").value

        //initialise une requete ajax
        const xmlhttp = new XMLHttpRequest

        xmlhttp.onreadystatechange = () => {
            //si la requete est terminée
            if(xmlhttp.readyState == 4){
                //si on a une réponse
                if(xmlhttp.status == 200){
                    //on récup la rép
                    let response = JSON.parse(xmlhttp.response)
                    console.log(address)
                    console.log(response)
                    console.log(XMLHttpRequest)
                    let lat = response[0]['lat']
                    let lon = response[0]['lon']
                    document.querySelector("#center_form_lat").value = lat
                    document.querySelector("#center_form_lon").value = lon

                    let pos = [lat, lon]
                    addMarker(pos)

                    let contentPopup = response[0]['display_name']
                    marker.bindPopup(contentPopup)

                }
            }
        }

        //on ouvre la requete
        xmlhttp.open("get", `https://nominatim.openstreetmap.org/search?q=${address}&format=json&addessdetails=1&limit=1&polygon_svg=1`)

        xmlhttp.send()
    }
}

<!DOCTYPE html>
<html>

<head>

  <title>webgis Dusun</title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
  <link rel="stylesheet" href="asset/css/app.css">



  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.5/typeahead.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>

  <script src="https://unpkg.com/rbush@2.0.2/rbush.min.js"></script>
  <script src="https://unpkg.com/labelgun@6.1.0/lib/labelgun.min.js"></script>

  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>

  <style>
    .styleLabel {
      background: rgba(255, 255, 255, 0);
      border: 0;
      border-radius: 0px;
      box-shadow: 0 0px 0px;
      font-size: 10pt;
      color: black;
      text-shadow: 2px 2px 5px white;
    }
  </style>
</head>
<body>

  <div id="mapid"></div>


  <div class="container">
    <div class="modal fade" id="featureModal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-primary" id="feature-title"></h4>
            <h4 class=""></h4>
          </div>
          <div class="modal-body" id="feature-info"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>

  <script>
    var map = L.map('mapid').setView([-7.970790, 110.259063], 17);

    L.tileLayer(
      'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 23,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
      }).addTo(map);

      //memanggil data geojson titik
      let titikrumah = L.geoJSON(null, {
      pointToLayer: function (feature, latlng) {

        let icon 
        switch (feature.properties.RT) {
          case 'RT01':
            icon = 'asset/img/home1.png'
            break;
          case 'RT02':
            icon = 'asset/img/home2.png'
            break;
          case 'RT03':
            icon = 'asset/img/home3.png'
            break;
          case 'RT04':
            icon = 'asset/img/home4.png'
            break;
          default:
        }
        
        return L.marker(latlng, {
          icon : L.icon({
            iconUrl : icon,
            iconSize: [28,28],
            iconAnchor: [14,34]
          })
        })
      },

      onEachFeature: function (feature, layer){
        if (feature.properties){
        
        let content = "";
        feature.properties.anggota.forEach(myfunction);

        function myfunction(item, index) {
          content += "<table class='table table-striped table-bordered table-condensed'>" + 
          "<tr><th>name</th><td>" + feature.properties.anggota[index].nama + "</td></tr>"+
          "<tr><th>Status</th><td>" + feature.properties.anggota[index].status + "</td></tr>"+
          "<tr><th>tanggal Lahir</th><td>" + feature.properties.anggota[index].tgllhr + "</td></tr>"+
          "<tr><th>Jenis Kelamin</th><td>" + feature.properties.anggota[index].jeniskelamin + "</td></tr>"+"</table>";
        }

        layer.on({
          click: function(e){
            $("#feature-title").html("Kepala Keluarga " + feature.properties.rumah +" "+ feature.properties.RT);
            $("#feature-info").html(content);
            $("#featureModal").modal("show");
          }
        });


        layer.bindTooltip(layer.feature.properties.rumah.toString(),{
            direction: 'center',
            permanent: true,
            className: 'styleLabel'
        });
        }

      }
      });

      $.getJSON("asset/php/datagabungan.php", function (data){
        titikrumah.addData(data);
        map.addLayer(titikrumah);
      });

  </script>

</body>

</html>
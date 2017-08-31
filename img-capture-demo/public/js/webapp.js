new Vue({
  el: '#app',
  data: {
    date: "",
    images: [],
    manHole: false,
    gps: {
      lat: "",
      long: "",
      accuracy: "",
      altitude: "",
      altAccuracy: "",
      heading: "",
      speed: "",
      timestamp: ""
    },
    selected: {
      type: "",
      network: "",
      status: "",
      diameter: ""
    },
    types: [
      "Manhole",
      "Infiltration / Inflow",
      "Connections",
      "Other"
    ],
    networks: [
      "Sewage",
      "Storm",
      "Combined",
      "Other"
    ],
    statuses: [
      "Located",
      "Buried",
      "New",
      "Not on GIS"
    ],
    diameters: [
      "1050",
      "1200",
      "1400",
      "1500",
      "1650",
      "1800",
      "2050",
      "2300",
      "2550",
      "3000",
      "3200"
    ]
  },
  components: {
    'image-container': {
      template: '#image-container'
    }
  },
  methods: {
    optionSelect: function() {
      // Check if manhole is selected onchange, hide unhide ui
      return (this.selected.type == "Manhole") ? this.manHole = true : this.manHole = false;
    },
    getLocation: function() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(this.showPosition);
      } else {
        console.log('Geolocation not supported');
      }
    },
    showPosition: function(position) {
      console.log(position);
      this.gps.lat = position.coords.latitude;
      this.gps.long = position.coords.longitude;
      this.gps.accuracy = position.coords.accuracy;
      this.gps.altitude = position.coords.altitude;
      this.gps.altAccuracy = position.coords.altitudeAccuracy;
      this.gps.heading = position.coords.heading;
      this.gps.speed = position.coords.speed;
      this.gps.timestamp = position.coords.timestamp;
    },
    getDate: function() {
      return this.date = Date();
    },
    getImage: function() {
      var x = this.$refs.inputOne;
      this.getImageDetails(x);
    },
    getImageDetails: function(x) {
      var array = this.images;
      var reader = new FileReader();

      if ('files' in x) {
        if (x.files.length == 0) {
          console.log("Select one or more files.");
        } else {
          for (var i = 0; i < x.files.length; i++) {
            reader.onload = (x) => {
              array.push(x.target.result);
            };
            reader.readAsDataURL(x.files[i]);
          }
        }
      }
    },
    removeImage: function(e) {
      this.image = '';
    }
  }
})

new Vue({
  el: '#app',
  data: {
    lat: "",
    long: "",
    date: "",
    images: [],
    image: "",
    manHole: false,
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
  methods: {
    optionSelect: function() {
      if (this.selected.type == "Manhole") {
        this.manHole = true;
      } else {
        return this.manHole = false;
      }
    },
    getLocation: function() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(this.showPosition);
      } else {
        console.log('Geolocation not supported');
      }
    },
    showPosition: function(position) {
      this.lat = position.coords.latitude;
      this.long = position.coords.longitude;
    },
    getDate: function() {
      return this.date = Date();
    },
    imageOne: function() {
      var x = this.$refs.inputOne;
      this.getImageDetails(x);
    },
    imageTwo: function() {
      var x = this.$refs.inputTwo;
      this.getImageDetails(x)
    },
    getImageDetails: function(x) {
      var array = this.images;

      if ('files' in x) {
        if (x.files.length == 0) {
          console.log("Select one or more files.");
        } else {

          for (var i = 0; i < x.files.length; i++) {
            var file = x.files[i];
            var image = new Image();
            var reader = new FileReader();
            var vm = this;
            reader.onload = (x) => {
              vm.image = x.target.result;
            };
            reader.readAsDataURL(file);
            if ('name' in file) {
              array.push(file.name);
            }
            if ('webkitRelativePath' in file) {
              array.push(file.webkitRelativePath);
            }
          }
        }
      }
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      this.createImage(files[0]);
    },
    createImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;

      reader.onload = (e) => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    removeImage: function(e) {
      this.image = '';
    },
    changeItem: function changeItem(rowId, event) {
      this.selected2 = rowId + ", " + event.target.value;
      this.selected = event.target.value;
    }
  }
})

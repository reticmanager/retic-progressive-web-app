new Vue({
	el: '#app',
	data: {
		lat: "",
		long: "",
		date: "",
		images: []
	},
	methods: {
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
						if ('name' in file) {
							array.push(file.name);
						}
						if ('size' in file) {
							array.push(file.size);
						}
					}
				}
			}
		}
	}
})

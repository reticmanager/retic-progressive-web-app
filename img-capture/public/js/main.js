new Vue({
  el: '#app',
  data: {

  },
  mounted: function () {
      console.log("Yes");
      window.awe.init({

        device_type: awe.AUTO_DETECT_DEVICE_TYPE,
        settings: {
          container_id: 'container',
          default_camera_position: {
            x: 0,
            y: 0,
            z: 0
          },
          default_lights: [{
              id: 'ambient_light',
              type: 'ambient',
              color: 0x666666
            },
            {
              id: 'hemi',
              type: 'hemisphere',
              color: 0xCCCCCC,
            },
          ],
        },
        ready: function() {
          var d = '?_=' + Date.now();

          awe.util.require([{
              capabilities: ['gum', 'webgl'],
              files: [
                // base libraries
                ['js/awe-standard-dependencies.js', 'js/awe-standard.js' + d],
                // plugin dependencies
                ['awe-jsartoolkit-dependencies.js', 'js/plugins/StereoEffect.js', 'js/plugins/VREffect.js'],
                // plugins
                ['awe.marker_ar.js' + d, 'js/plugins/awe.rendering_effects.js' + d]
              ],
              success: function() {
                awe.setup_scene();

                awe.settings.update({
                  data: {
                    value: 'ar'
                  },
                  where: {
                    id: 'view_mode'
                  }
                })

                /*
                  Binding a POI to a jsartoolkit marker is easy
  First add the awe - jsartoolkit-dependencies.js plugin (see above)
                  - Then select a marker image you'd like to use
                  - Then add the matching number as a suffix for your POI id (e.g. _64)
                  NOTE: See 64.png in this directory or https://github.com/kig/JSARToolKit/blob/master/demos/markers
                  This automatically binds your POI to that marker id - easy!
                */
                awe.pois.add({
                  id: 'jsartoolkit_marker_64',
                  position: {
                    x: 0,
                    y: 0,
                    z: 0
                  },
                  visible: false
                });
                awe.projections.add({
                  id: 'marker_projection',
                  geometry: {
                    shape: 'cube',
                    x: 50,
                    y: 50,
                    z: 50
                  },
                  position: {
                    x: 0,
                    y: 0,
                    z: 0
                  },
                  rotation: {
                    x: 0,
                    y: 0,
                    z: 180
                  },
                  material: {
                    type: 'phong',
                    color: 0xFFFFFF
                  },
                  texture: {
                    path: 'logo.png'
                  },
                  visible: false,
                }, {
                  poi_id: 'jsartoolkit_marker_64'
                });

                awe.pois.add({
                  id: 'fixed_poi',
                  position: {
                    x: 100,
                    y: 0,
                    z: -250
                  },
                  visible: true
                });
                awe.projections.add({
                  id: 'fixed_projection',
                  geometry: {
                    shape: 'cube',
                    x: 50,
                    y: 50,
                    z: 50
                  },
                  position: {
                    x: 0,
                    y: 0,
                    z: 0
                  },
                  rotation: {
                    x: 0,
                    y: 0,
                    z: 0
                  },
                  material: {
                    type: 'phong',
                    color: 0xFFFFFF
                  },
                  texture: {
                    path: 'logo.png'
                  },
                }, {
                  poi_id: 'fixed_poi'
                });

                // animate the fixed POI
                awe.projections.update({
                  data: {
                    animation: {
                      duration: 5,
                      persist: 0,
                      repeat: Infinity
                    },
                    rotation: {
                      y: 360
                    },
                  },
                  where: {
                    id: "fixed_projection"
                  },
                });

                awe.plugins.view('render_effects').enable();
                awe.plugins.view('jsartoolkit').enable();
              },
            },
            {
              capabilities: [],
              success: function() {
                alert('Try this demo in the latest version of Chrome or Firefox on a PC or Android device')
                // document.body.innerHTML = '<p>Try this demo in the latest version of Chrome or Firefox on a PC or Android device</p>';
              },
            },
          ]);
        }
      });
    }
  })

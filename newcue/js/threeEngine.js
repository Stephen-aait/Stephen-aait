var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera(75,window.innerWidth / window.innerHeight, 0.1,1000);

var renderer = new THREE.WebGLRenderer();

var CueBag3dCanvas = document.getElementById('CueBag3dCanvas');
renderer.setSize(CueBag3dCanvas.offsetWidth, CueBag3dCanvas.offsetHeight);

CueBag3dCanvas.appendChild(renderer.domElement);


window.addEventListener('resize', function(){
  var width = CueBag3dCanvas.offsetWidth;
  var height = CueBag3dCanvas.offsetHeight;
  renderer.setSize(width, height);
  camera.aspect = width / height;
  camera.updateProjectionMatrix();
});

var manager = new THREE.LoadingManager();
manager.onProgress = function(item, loaded, total) {
   console.log(item, loaded, total);
 };

 var onProgress = function(xhr) {
   $('body').loadingModal({
     position: 'auto',
      text: '',
      color: '#fff',
      opacity: '0.7',
      backgroundColor: 'rgb(0,0,0)',
      animation: 'doubleBounce'
    });
   if (xhr.lengthComputable) {
     var percentComplete = xhr.loaded / xhr.total * 100;
     console.log(Math.round(percentComplete, 2) + '% downloaded');
     if(percentComplete == 100){
       $('body').loadingModal('destroy');
     }
   }

 };

controls = new THREE.OrbitControls(camera,renderer.domElement);
controls.enableKeys = false;
controls.enablePan = false;
controls.minDistance = 30;
controls.maxDistance = 50;

var texture = new THREE.Texture();
var textureImageLoader = new THREE.ImageLoader(manager);
textureImageLoader.load("asset/model/cuebag/cuebag.png", function(image){
    texture.image = image;
    texture.needsUpdate = true;
});

var mtl = new THREE.MTLLoader()
					.setPath( 'asset/model/cuebag/' )
          .setTexturePath("asset/model/cuebag/cuebag (5).png")
					.load( 'cuebag.mtl', function ( materials ) {
						materials.preload();
						var objLoader = new THREE.OBJLoader()
							.setMaterials( materials )
							.setPath( 'asset/model/cuebag/' )
							.load( 'cuebag.obj', function ( object ) {

                object.traverse(function(child){
                if(child instanceof THREE.Mesh){
                  child.material.map = texture;
                }
              });

								object.position.y = - 30;
								scene.add( object );
							}, onProgress );
					} );


camera.position.z = 45;

var ambientLight = new THREE.AmbientLight(0xFFFFFF, 1);
scene.add(ambientLight);

scene.background = new THREE.Color(0xF5F5F5);

//animation logic
var update = function(){
  console.log(camera.position.z);
};

//draw Scene
var render = function(){
  renderer.render(scene,camera);
};

//run animation Loop(update,render,repeat)
var animationLoop = function(){
  requestAnimationFrame(animationLoop);
  update();
  render();
};

function blueTexture(){
  textureImageLoader.load("asset/model/cuebag/cuebag.png", function(image){
      texture.image = image;
      texture.needsUpdate = true;
  });
}

function redTexture(){
  textureImageLoader.load("asset/model/cuebag/cuebag (5).png", function(image){
      texture.image = image;
      texture.needsUpdate = true;
  });
}

function blackTexture(){
  textureImageLoader.load("asset/model/cuebag/cuebag (6).png", function(image){
      texture.image = image;
      texture.needsUpdate = true;
  });
}

function yellowTexture(){
  textureImageLoader.load("asset/model/cuebag/cuebag (1).png", function(image){
      texture.image = image;
      texture.needsUpdate = true;
  });
}



animationLoop();

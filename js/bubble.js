var follower = document.getElementById('slider-follow');
var follower_val = document.getElementById('slider-val');
var slider = document.getElementById('frame-slider');

var updateFollowerValue = function(val) {
  follower_val.innerHTML = val;
  follower.style.left = (val*1) + '%';
};
updateFollowerValue(slider.value);
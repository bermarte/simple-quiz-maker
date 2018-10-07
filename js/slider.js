function updateSlider(a,b,c){
    var slider = document.getElementById(a);
    var output = document.getElementById(b);
    var output2 = document.getElementById(c);
    output.value = slider.value;

    slider.onchange = function() {
        output.value = this.value;
        output2.value = this.value;
    }
    
}
updateSlider("frame-slider","percentageInputDiv", "percentageInputDiv");